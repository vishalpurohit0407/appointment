<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Config;

class PatientReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $maildetails = $this->details;
        $aa = view('mail.patient-report')->with(compact('maildetails'))->render();

        return $this->from(Config::get('mail.from.address'), Config::get('mail.from.name'))
            ->subject('Patient Report')
            ->view('mail.patient-report')
            ->with(compact('maildetails'));
    }
}
