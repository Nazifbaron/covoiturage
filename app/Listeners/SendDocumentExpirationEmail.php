<?php

namespace App\Listeners;

use App\Events\VehicleDocumentExpiringSoon;
use App\Mail\VehicleDocumentExpiring;
use Illuminate\Support\Facades\Mail;

class SendDocumentExpirationEmail
{
    public function handle(VehicleDocumentExpiringSoon $event): void
    {
        $driver = $event->vehicle->driver;

        if (!$driver || !$driver->email) {
            return;
        }

        Mail::to($driver->email)->send(
            new VehicleDocumentExpiring($event->vehicle, $event->expiringDocs)
        );
    }
}
