<?php

namespace App\Mail;

use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewVehicleSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Vehicle $vehicle)
    {
        
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Covoiturage] Nouveau véhicule à valider — ' . $this->vehicle->brand . ' ' . $this->vehicle->model,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-vehicle-submitted',
            with: [
                'vehicle' => $this->vehicle,
                'driver'  => $this->vehicle->driver,
                'adminUrl' => route('admin.vehicles'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
