<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Mail\SendReport;
use App\Models\Admin\Report;
use App\Models\Admin\ReportQuery;
use App\Models\Admin\Contact;
use App\Models\Admin\ContactGroup;
use App\Models\Admin\ConfigDatabase;
use Illuminate\Support\Facades\Bus;

class SendReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function handle()
    {
        // Obtendo a consulta do relatório
        $querySelect = ReportQuery::where('report_id', $this->report->id)->first();
        $group = ContactGroup::find($this->report->group_id);

        if (!$group || $group->status != 1 || $this->report->status != 1) {
            \Log::warning("Relatório ID {$this->report->id} ignorado: grupo ou relatório inativo.");
            return;
        }

        if (!$this->report->group_id || !$querySelect || !$querySelect->query) {
            \Log::warning("Relatório ID {$this->report->id} ignorado: sem grupo ou query.");
            return;
        }

        if (!preg_match('/^\s*SELECT\s+/i', $querySelect->query)) {
            \Log::warning("Relatório ID {$this->report->id} ignorado: apenas SELECTs são permitidos.");
            return;
        }

        // Configuração do banco
        $config = ConfigDatabase::first();
        if (!$config) {
            \Log::error("Configuração da Database não foi encontrada.");
            return;
        }

        $connectionName = $config->db_connection;
        Config::set("database.connections.$connectionName", [
            'driver'    => $config->db_connection === 'oci8' ? 'oracle' : 'mysql',
            'host'      => $config->host,
            'port'      => $config->port,
            'database'  => $config->database,
            'username'  => $config->username,
            'password'  => Crypt::decryptString($config->password),
            'charset'   => $config->charset ?? 'utf8mb4',
            'collation' => $config->collation ?? 'utf8mb4_unicode_ci',
        ]);

        DB::purge($connectionName);

        try {
            $contacts = Contact::where('group_id', $this->report->group_id)
                ->where('status', 1)
                ->get()
                ->filter(fn($contact) => !empty($contact->email));

            if ($contacts->isEmpty()) {
                \Log::warning("Relatório ID {$this->report->id} ignorado: sem contatos válidos.");
                return;
            }

            $result = DB::connection($connectionName)->select($querySelect->query);
            $configMail = DB::table('config_mails')->first();
            $signature = $configMail->signature ?? '';
            //$batch = Bus::batch([])->name("Envio de Relatório")->dispatch();

            foreach ($contacts as $contact) {
                \Log::info("Enviando relatório {$this->report->id} para {$contact->email}");
                Mail::to($contact->email)->queue(new SendReport($this->report, $result, $signature));
                //$batch->add(new SendReport($this->report, $result, $signature));
            }

            \Log::info("Relatório ID {$this->report->id} enviado com sucesso.");
        } catch (\Exception $e) {
            \Log::error("Erro ao processar relatório ID {$this->report->id}: " . $e->getMessage());
        }
    }
}
