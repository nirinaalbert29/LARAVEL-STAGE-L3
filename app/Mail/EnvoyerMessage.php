<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnvoyerMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $messageContent;

    public function __construct($message)
    {
        $this->messageContent = $message;
    }

    public function build()
    {
        return $this->view('emails.envoyer')
            ->subject('Nouveau message')
            ->text('emails.envoyer_plain');
    }
}
