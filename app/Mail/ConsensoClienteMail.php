<?php

namespace App\Mail;

use App\Models\Consenso;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConsensoClienteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $consenso;

    public function __construct(Consenso $consenso)
    {
        $this->consenso = $consenso;
    }

    public function build()
    {
        return $this
            ->subject('Consenso Informato Boudoir31')

            ->view('emails.consenso')

            ->attach(
                storage_path(
                    'app/'.$this->consenso->pdf_path
                )
            );
    }
}