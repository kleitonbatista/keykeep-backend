<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TesteEmailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $userEmail
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bem-vindo ao KeyKeep - E-mail de Teste',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.teste',
            with: [
                'email' => $this->userEmail,
            ],
        );
    }
}
