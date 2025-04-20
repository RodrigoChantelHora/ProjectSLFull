<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Comando de inspiração que já vem no Laravel
// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

// Adiciona o comando de envio de relatórios agendados ao Schedule
Schedule::command('app:send-scheduled-reports')->everyMinute();
