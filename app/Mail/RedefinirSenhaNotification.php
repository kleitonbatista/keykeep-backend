<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RedefinirSenhaNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $url
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'KeyKeep - Redefinição de Senha',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reset-password',
            with: [
                'user' => $this->user,
                'url'  => $this->url,
            ],
        );
    }
}
