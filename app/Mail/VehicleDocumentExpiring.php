<?php

namespace App\Mail;

use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VehicleDocumentExpiring extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Vehicle $vehicle,
        public array $expiringDocs
    ) {}

    public function envelope(): Envelope
    {
        $urgence = collect($this->expiringDocs)->min('days_remaining') <= 7
            ? '⚠️ URGENT — '
            : '';

        return new Envelope(
            subject: $urgence . '[Covoiturage] Vos documents de véhicule expirent bientôt',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.vehicle-document-expiring',
            with: [
                'vehicle'     => $this->vehicle,
                'driver'      => $this->vehicle->driver,
                'docs'        => $this->expiringDocs,
                'profileUrl'  => route('profile.edit'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
