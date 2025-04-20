<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Report;
use App\Jobs\SendReportJob;
use Carbon\Carbon;

class SendScheduledReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-scheduled-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia relatórios agendados conforme a programação definida';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Verificando relatórios agendados...");

        $now = now()->timezone('America/Sao_Paulo');
        $reports = Report::where('status', true)->get();

        foreach ($reports as $report) {
            if ($this->shouldRunTask($report, $now)) {
                $this->info("Agendando envio do relatório ID {$report->id}");
                SendReportJob::dispatch($report);
            }
        }

        $this->info("Processo concluído.");
    }

    /**
     * Determina se o relatório deve ser enviado agora.
     */
    protected function shouldRunTask($report, $now): bool
    {
        return match ($report->scheduleType) {
            'daily' => $now->format('H:i') === $report->selectedTime,
            'weekly' => $now->format('l') === $report->selectedWeekday && $now->format('H:i') === $report->selectedTime,
            'monthly' => $now->format('j') == $report->selectedDay && $now->format('H:i') === $report->selectedTime,
            'yearly' => $now->format('j') == $report->selectedDay &&
                        $now->format('n') == $report->selectedMonth &&
                        $now->format('H:i') === $report->selectedTime,
            default => false,
        };
    }
}
