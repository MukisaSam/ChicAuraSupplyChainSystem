<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $htmlContent;
    public $subjectLine;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($htmlContent, $subjectLine = 'Weekly Admin Report')
    {
        $this->htmlContent = $htmlContent;
        $this->subjectLine = $subjectLine;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subjectLine)
            ->html($this->htmlContent);
    }
} 