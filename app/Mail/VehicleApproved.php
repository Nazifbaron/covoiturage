<?php

namespace App\Mail;

use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VehicleApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Vehicle $vehicle)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Covoiturage] Votre véhicule a été approuvé !',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.vehicle-approved',
            with: [
                'vehicle'      => $this->vehicle,
                'driver'       => $this->vehicle->driver,
                'dashboardUrl' => route('dashboard'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
