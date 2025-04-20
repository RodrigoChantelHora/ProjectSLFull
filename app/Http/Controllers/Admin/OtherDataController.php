<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Client;
use Illuminate\Http\Request;

class OtherDataController extends Controller
{
    public function totalStorage()
    {
        // Diretório que você quer verificar (ex: /var/www/html)
        $directory = "/"; // Altere isso para o diretório desejado

        // Espaço total em bytes
        $totalSpace = disk_total_space($directory);

        // Espaço livre em bytes
        $freeSpace = disk_free_space($directory);

        // Espaço usado em bytes
        $usedSpace = $totalSpace - $freeSpace;

        // Converter de bytes para gigabytes
        $totalSpaceGB = round($totalSpace / 1024 / 1024 / 1024, 2); // Total em GB
        $usedSpaceGB = round($usedSpace / 1024 / 1024 / 1024, 2); // Usado em GB
        $freeSpaceGB = round($freeSpace / 1024 / 1024 / 1024, 2); // Livre em GB
        
        return response()->json([
            'user' => 'Rodrigo',
            'maxStorage' => $totalSpaceGB,
            'usedStorage' => $usedSpaceGB,
            'freeStorage' => $freeSpaceGB,
        ]);
    }

    public function globalClients()
    {
        $countCli = Client::count();

        return response()->json([
            'countCli' => $countCli,
        ]);
    }
}
