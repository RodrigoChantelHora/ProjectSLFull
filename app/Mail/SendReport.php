<?php

namespace App\Mail;

use App\Exports\QueryReportExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class SendReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $report;
    public $result;
    public $signature;

    /**
     * Create a new message instance.
     */
    public function __construct($report, $result, $signature)
    {
        $this->report = $report;
        $this->result = $result;
        $this->signature = $signature;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->report->report_description,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails/SendMailGroup',
            with: [
                'report' => $this->report,
                'signature' => $this->signature,
            ],
        );
    }


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => Excel::raw(new QueryReportExport($this->result), \Maatwebsite\Excel\Excel::XLSX),
                $this->report->report_description . '.xlsx'
            )->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        ];
    }
}
