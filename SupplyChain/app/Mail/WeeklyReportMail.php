<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $csv;
    public $type;

    public function __construct($user, $csv, $type)
    {
        $this->user = $user;
        $this->csv = $csv;
        $this->type = $type;
    }

    public function build()
    {
        $filename = $this->type . '_weekly_report.csv';
        return $this->subject('Your Weekly ' . ucfirst($this->type) . ' Report')
            ->view('emails.weekly_report')
            ->attachData($this->csv, $filename, [
                'mime' => 'text/csv',
            ]);
    }
} 