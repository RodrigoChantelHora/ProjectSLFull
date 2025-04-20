<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ConfigMail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ConfigMailController extends Controller
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
    public function show(ConfigMail $configMail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConfigMail $configMail):Response
    {
        $mailConfig = ConfigMail::first();

        return Inertia::render('Admin/Config/ConfigMail', [
            'mailConfig'    => $mailConfig,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConfigMail $configMail)
    {
        $SendMailConfig = ConfigMail::updateOrCreate(
            ['id' => $request->id],
            [
                'mailer'            => $request->mailer,
                'host'              => $request->host,
                'port'              => $request->port,
                'username'          => $request->username,
                'password'          => $request->password,
                'encryption'        => $request->encryption,
                'from_adress'       => $request->from_adress,
                'from_name'         => $request->from_name,
                'signature'         => $request->signature ?? '',
            ]
        );

        return redirect()->back()->with('success', 'Informações atualizadas com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConfigMail $configMail)
    {
        //
    }
}
