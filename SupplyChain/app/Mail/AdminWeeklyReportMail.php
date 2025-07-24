<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class WeeklyReportMail extends Mailable
{
    use Queueable, SerializesModels;


    public $reportData;
    public $user;
    public $type;

    public function __construct($reportData, $user, $type = 'summary')
{
    $this->reportData = $reportData;
    $this->user = $user;
    $this->type = $type;
}

public function build()
{
    $pdf = Pdf::loadView('pdf.weekly-report', ['reportData' => $this->reportData]);

    return $this->subject('Weekly Report')
                ->view('emails.weekly-report')
                ->with([
                    'reportData' => $this->reportData,
                    'user' => $this->user,
                    'type' => $this->type,
                ])
                ->attachData($pdf->output(), 'weekly_report.pdf');
}


}

