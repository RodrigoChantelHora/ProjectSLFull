<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetCreateReportRequest;
use App\Http\Requests\SetReportRequest;
use App\Mail\SendReport;
use App\Models\Admin\ConfigDatabase;
use App\Models\Admin\ConfigMail;
use App\Models\Admin\Contact;
use App\Models\Admin\ContactGroup;
use App\Models\Admin\QueryReport;
use App\Models\Admin\Report;
use App\Models\Admin\ReportQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():Response
    {
        $reports = Report::with('group')->paginate(10);

        return Inertia::render('Admin/Reports/ReportIndex', [
            'reports' => $reports,
            'countReports' => $reports->total(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():Response
    {
        $groups = ContactGroup::all();
        $countReports = Report::count();

        return Inertia::render('Admin/Reports/ReportCreate', [
            'groups' => $groups,
            'countReports'  => $countReports,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SetReportRequest $request)
    {
        $report = Report::create([
            'report_description'    => $request->report_description,
            'status'    => $request->status,
            'group_id'  => $request->group_id,
            'message'   => $request->message,
            'scheduleType'  => $request->scheduleType,
            'selectedTime'  => $request->selectedTime,
            'selectedWeekday'   => $request->selectedWeekday,
            'selectedDay'   => $request->selectedDay,
            'selectedMonth' => $request->selectedMonth,
            'report_token'  => Str::random(64),
            'user_id'       => Auth::user()->id,
        ]);

        $report->save();

        $query = ReportQuery::create([
            'query_name' => $request->input('query_name'),
            'query' => $request->input('query'),
            'query_token' => Str::random(64),
            'report_id' => $report->id,
        ]);

        $query->save();
        return redirect()->route('report.index')
        ->with('success', 'Relatório cadastrado com sucesso! Por favor, verifique a execução da query.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report, $id, $token)
    {
        $report = Report::where('id', $id)->where('report_token', $token)->first();
        $countReports = Report::count();
        $groups = ContactGroup::get();
        $query = ReportQuery::where('report_id', $report->id)->first();

        return Inertia::render('Admin/Reports/ReportEdit', [
            'report'    => $report,
            'countReports' => $countReports,
            'groups'        => $groups,
            'query'         => $query,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SetReportRequest $request, Report $report)
    {
        $report = Report::where('id', $request->report_id)->where('report_token', $request->report_token)->update([
            'report_description'    => $request->report_description,
            'status'    => $request->status,
            'group_id'  => $request->group_id,
            'message'   => $request->message,
            'scheduleType'  => $request->scheduleType,
            'selectedTime'  => $request->selectedTime,
            'selectedWeekday'   => $request->selectedWeekday,
            'selectedDay'   => $request->selectedDay,
            'selectedMonth' => $request->selectedMonth,
        ]);

        if($report) {

            $reportSelect = Report::where('id', $request->report_id);

            if(ReportQuery::where('id', $request->query_id)) {
                $query = ReportQuery::where('id', $request->query_id)->where('query_token', $request->query_token)->update([
                    'query_name' => $request->input('query_name'),
                    'query' => $request->input('query'),
                ]);

                return redirect()->route('report.index')->with('success', 'Relatório atualizado com sucesso!');
            }

            $query = ReportQuery::create([
                'query_name' => $request->input('query_name'),
                'query' => $request->input('query'),
                'query_token' => Str::random(64),
                'report_id' => $reportSelect->id,
            ]);

            $query->save();
            return redirect()->route('report.index')->with('success', 'Consulta adicionada com sucesso!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report, $id, $token)
    {
        Report::where('id', $id)->where('report_token', $token)->delete();

        return redirect()->back()->with('success', 'Deletado com sucesso!');
    }

    public function search(Request $request)
    {
        $reports = Report::with('group')->paginate(10);

        if($request->search) {
            $reports = Report::with('group')
            ->where('report_description', 'like', '%' . $request->search . '%')
            ->paginate(10);
        }

        return Inertia::render('Admin/Reports/ReportIndex', [
            'reports'    => $reports,
            'countReports' => $reports->total(),
        ]);
    }

    public function sendReport(Request $request, $id, $token)
    {
        $report = Report::with(['user', 'group'])->where('report_token', $token)->find($id);
        $querySelect = ReportQuery::where('report_id', $id)->first();
        $group = ContactGroup::find($report->group_id);

        if($group->status != 1 || $report->status != 1) {
            return redirect()->back()->with('error', 'Relatório ou grupo com status inativo');
        }

        if (!$report->group_id || !$querySelect->query) {
            return redirect()->back()->with('error', 'Nenhum grupo ou consulta vinculado ao relatório.');
        }

        // Segurança: apenas SELECTs
        if (!preg_match('/^\s*SELECT\s+/i', $querySelect->query)) {
            return redirect()->back()->with('error', 'Apenas consultas via SELECT serão permitidas.');
        }

        $config = ConfigDatabase::first();

        if (!$config) {
            return redirect()->back()->with('error', 'Configuração da Database não foi encontrada.');
        }

        switch ($config->db_connection) {
            case 'oci8':
                $driver = 'oracle';
                $charset = 'AL32UTF8';
                $collation = 'AL32UTF8';
                break;
            case 'mysql':
                $driver = 'mysql';
                $charset = 'utf8mb4';
                $collation = 'utf8mb4_unicode_ci';
                break;
            default:
                return redirect()->back()->with('error', 'Tipo de conexão não suportado.');
        }

        $connectionName = $config->db_connection;

        Config::set("database.connections.$connectionName", [
            'driver'    => $driver,
            'host'      => $config->host,
            'port'      => $config->port,
            'database'  => $config->database,
            'username'  => $config->username,
            'password'  => Crypt::decryptString($config->password),
            'charset'   => $config->charset ?? $charset,
            'collation' => $config->collation ?? $collation,
        ]);

        DB::purge($connectionName);

        // OBTENDO DADOS DOS CONTATOS POR GRUPO

        $contacts = Contact::where('group_id', $report->group_id)->where('status', 1)->get()->filter(fn($contact) => !empty($contact->email));

        if ($contacts->isEmpty()) {
            return redirect()->back()->with('error', 'Por valor verifique os contatos dentro do grupo.');
        }

        try {
            $result = DB::connection($connectionName)->select($querySelect->query);
            
            foreach ($result as &$row) {
                foreach ($row as $key => $value) {
                    if (is_string($value)) {
                        $row->$key = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                    }
                }
            }

            $configMail = DB::table('config_mails')->first();
            $signature = $configMail->signature;

            Config::set('mail.mailers.smtp.host', $configMail->host);
            Config::set('mail.mailers.smtp.port', $configMail->port);
            Config::set('mail.mailers.smtp.username', $configMail->username);
            Config::set('mail.mailers.smtp.password', $configMail->password);
            Config::set('mail.mailers.smtp.encryption', $configMail->encryption); // Pode ser null
            Config::set('mail.from.address', $configMail->from_adress);
            Config::set('mail.from.name', $configMail->from_name);

            try {
                foreach ($contacts as $contact) {
                    Mail::to($contact->email)->queue(new SendReport($report, $result, $signature));
                }

                return redirect()->back()->with('success', 'O e-mail foi adicionado a fila de envios.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Erro no envio de e-mail: ' . $e->getMessage());
            }


        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao executar comando: ' . $e->getMessage());
        }
    }
}
