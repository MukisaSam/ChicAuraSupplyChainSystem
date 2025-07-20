<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SupplierWeeklyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $htmlContent;
    public $subjectLine;
    public $supplier;

    public function __construct($htmlContent, User $supplier, $subjectLine = 'Your Weekly Supplier Report')
    {
        $this->htmlContent = $htmlContent;
        $this->subjectLine = $subjectLine;
        $this->supplier = $supplier;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->html($this->htmlContent);
    }
} 