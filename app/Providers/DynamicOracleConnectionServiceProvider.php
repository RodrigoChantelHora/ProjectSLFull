<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class DynamicOracleConnectionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            if (DB::getDefaultConnection() !== 'oci8') {
                // Use o banco padrão para buscar os dados da conexão Oracle
                $config = DB::table('config_databases')->where('driver', 'oracle')->first();

                if ($config) {
                    Config::set('database.connections.oci8', [
                        'driver'        => 'oracle',
                        'tns'           => $config->tns,
                        'host'          => $config->host,
                        'port'          => $config->port,
                        'database'      => $config->database,
                        'username'      => $config->username,
                        'password'      => $config->password,
                        'charset'       => $config->charset ?? 'AL32UTF8',
                        'prefix'        => $config->prefix ?? '',
                        'prefix_schema' => $config->prefix_schema ?? '',
                    ]);
                }
            }
        } catch (\Exception $e) {
            //logger()->warning('Erro ao configurar conexão Oracle: ' . $e->getMessage());
        }
    }
}
