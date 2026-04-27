<?php

namespace App\Mail;

use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VehicleDocumentExpired extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Vehicle $vehicle,
        public array $expiredDocs
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚫 [Covoiturage] Votre compte a été suspendu — Documents expirés',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.vehicle-document-expired',
            with: [
                'vehicle'    => $this->vehicle,
                'driver'     => $this->vehicle->driver,
                'docs'       => $this->expiredDocs,
                'profileUrl' => route('profile.edit'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
