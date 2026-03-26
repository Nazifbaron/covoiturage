<?php

namespace App\Mail;

use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VehicleRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Vehicle $vehicle)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Covoiturage] Votre véhicule n\'a pas pu être validé',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.vehicle-rejected',
            with: [
                'vehicle'  => $this->vehicle,
                'driver'   => $this->vehicle->driver,
                'setupUrl' => route('driver.vehicle.setup'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
