<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ConfigDatabase;
use App\Models\Admin\ReportQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ReportQueryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ReportQuery $reportQuery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReportQuery $reportQuery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReportQuery $reportQuery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportQuery $reportQuery)
    {
        //
    }

    public function testQuery(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['query_error' => 'Nenhuma consulta foi enviada.']);
        }

        // Segurança: apenas consultas SELECT são permitidas
        if (!preg_match('/^\s*SELECT\s+/i', $query)) {
            return response()->json(['query_error' => 'Apenas consultas SELECT são permitidas.']);
        }

        // Pegando as credenciais do banco de dados salvas na tabela config_databases
        $config = ConfigDatabase::first();

        if (!$config) {
            return response()->json(['connection_error' => 'Configuração de banco Oracle não encontrada.']);
        }

        // Nome único para conexão dinâmica
        $connectionName = 'oci8';

        // Define a nova conexão dinamicamente
        Config::set("database.connections.$connectionName", [
            'driver'         => 'oracle',
            'tns'            => '',
            'host'           => $config->host,
            'port'           => $config->port,
            'database'       => $config->database,
            'username'       => $config->username,
            'password'       => Crypt::decryptString($config->password),
            'charset'        => $config->charset ?? 'AL32UTF8',
            'collation'      => 'AL32UTF8',
        ]);

        // Limpa conexões anteriores com o mesmo nome
        DB::purge($connectionName);

        try {
            $result = DB::connection($connectionName)->select($query);
            return response()->json(['result' => $result]);
        } catch (\Exception $e) {
            return response()->json(['query_error' => 'Erro ao executar a consulta: ' . $e->getMessage()]);
        }
    }
}
