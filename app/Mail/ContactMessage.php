<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public string $senderName;
    public string $senderEmail;
    public string $emailSubject;  // Propriété interne différente
    public string $body;

    // Utilisez 'subject' comme paramètre du constructeur
    public function __construct(
        string $senderName,
        string $senderEmail,
        string $subject,  // ← Paramètre nommé 'subject'
        string $body,
    ) {
        $this->senderName = $senderName;
        $this->senderEmail = $senderEmail;
        $this->emailSubject = $subject;  // Assigner à la propriété interne
        $this->body = $body;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [$this->senderEmail],
            subject: '[Contact GreenPool] ' . $this->emailSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-message',
            with: [
                'senderName' => $this->senderName,
                'senderEmail' => $this->senderEmail,
                'subject' => $this->emailSubject,  // Passer 'subject' à la vue
                'body' => $this->body,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
