<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ConfigDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class ConfigDatabaseController extends Controller
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
    public function show(ConfigDatabase $configDatabase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConfigDatabase $configDatabase):Response
    {
        $connection = ConfigDatabase::first();

        return Inertia::render('Admin/Config/ConfigDatabase', [
            'connection'    => $connection,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if(!$request->id) {
            $connectionDb = ConfigDatabase::create([
                'db_connection' => $request->db_connection,
                'host'          => $request->host,
                'port'          => $request->port,
                'database'      => $request->database,
                'username'      => $request->username,
                'password'      => Crypt::encryptString($request->password),
                'sid'           => $request->sid,
                'charset'       => $request->charset,
                'collation'     => $request->collation,
            ]);
            $connectionDb->save;
            return redirect()->back()->with('success', 'Informações atualizadas com sucesso!');
        }

        $connectionDb = ConfigDatabase::findOrFail($request->id);

        $connectionDb->update([
            'db_connection' => $request->db_connection,
            'host'          => $request->host,
            'port'          => $request->port,
            'database'      => $request->database,
            'username'      => $request->username,
            'password'      => Crypt::encryptString($request->password),
            'sid'           => $request->sid,
            'charset'       => $request->charset,
            'collation'     => $request->collation,
        ]);

        return redirect()->back()->with('success', 'Informações atualizadas com sucesso!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConfigDatabase $configDatabase)
    {
        //
    }
}
