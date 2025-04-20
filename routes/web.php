<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ConfigDatabaseController;
use App\Http\Controllers\Admin\ConfigMailController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ContactGroupController;
use App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailStatusController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\OtherDataController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReportQueryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::get('/config/database', [ConfigDatabaseController::class, 'edit'])->name('connection.edit');
    Route::any('/config/database/atualizar', [ConfigDatabaseController::class, 'update'])->name('connection.update');
    //
    Route::get('/config/mail', [ConfigMailController::class, 'edit'])->name('mail.edit');
    Route::any('/config/mail/atualizar', [ConfigMailController::class, 'update'])->name('mail.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/test-query', [ReportQueryController::class, 'testQuery'])->name('test.query');
    Route::get('/relatorios/grupo/email/{id}/{token}', [ReportController::class, 'sendReport'])->name('report.sendReport');
    Route::get('/email-status', [EmailStatusController::class, 'index'])->name('email.status');
});

Route::middleware('auth')->group(function () {
    Route::get('/relatorios', [ReportController::class, 'index'])->name('report.index');
    Route::get('/relatorios/registro', [ReportController::class, 'create'])->name('report.create');
    Route::post('/relatorios/registro/salvar', [ReportController::class, 'store'])->name('report.store');
    Route::get('/relatorios/registro/editar/{id}/{token}', [ReportController::class, 'edit'])->name('report.edit');
    Route::any('/relatorios/registro/atualizar', [ReportController::class, 'update'])->name('report.update');
    Route::any('/relatorios/buscar', [ReportController::class, 'search'])->name('report.search');
    Route::get('/relatorios/deletar/{id}/{token}', [ReportController::class, 'destroy'])->name('report.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/contatos', [ContactController::class, 'index'])->name('contact.index');
    Route::get('/contatos/registro', [ContactController::class, 'create'])->name('contact.create');
    Route::post('/contatos/registro/enviar', [ContactController::class, 'store'])->name('contact.store');
    Route::get('/contatos/registro/editar/{id}/{token}', [ContactController::class, 'edit'])->name('contact.edit');
    Route::any('/contatos/registro/atualizar', [ContactController::class, 'update'])->name('contact.update');
    Route::get('/contato/deletar/{id}/{token}', [ContactController::class, 'destroy'])->name('contact.destroy');
    Route::any('/contatos/buscar', [ContactController::class, 'search'])->name('contact.search');
    //
    Route::get('/contatos/grupos', [ContactGroupController::class, 'index'])->name('contactGroup.index');
    Route::get('/contatos/grupos/registro', [ContactGroupController::class, 'create'])->name('contactGroup.create');
    Route::post('/contatos/grupos/registro/enviar', [ContactGroupController::class, 'store'])->name('contactGroup.store');
    Route::get('/contatos/grupos/registro/editar/{id}/{token}', [ContactGroupController::class, 'edit'])->name('contactGroup.edit');
    Route::post('/contatos/grupos/registro/atualizar', [ContactGroupController::class, 'update'])->name('contactGroup.update');
    Route::get('/contato/grupos/deletar/{id}/{token}', [ContactGroupController::class, 'destroy'])->name('contactGroup.destroy');
    Route::any('/contatos/grupos/buscar', [ContactGroupController::class, 'search'])->name('contactGroup.search');
});

Route::middleware('auth')->group(function () {
    Route::resource('/faturamento/faturas', InvoiceController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/data', [OtherDataController::class, 'totalStorage'])->name('data.totalStorage');
    Route::get('/globalClients', [OtherDataController::class, 'globalClients'])->name('data.globalClients');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notification.index');
    Route::post('/notifications/status', [NotificationController::class, 'update'])->name('notification.update');
});

Route::get('/register', function () {
    abort(403, 'Registro de novos usuários está desabilitado. Contate o Suporte');
})->name('register');

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');
// });
