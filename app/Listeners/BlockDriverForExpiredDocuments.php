<?php

namespace App\Listeners;

use App\Events\VehicleDocumentExpired;
use App\Mail\VehicleDocumentExpired as VehicleDocumentExpiredMail;
use Illuminate\Support\Facades\Mail;

class BlockDriverForExpiredDocuments
{
    public function handle(VehicleDocumentExpired $event): void
    {
        $driver = $event->vehicle->driver;

        if (!$driver) {
            return;
        }

        // Bloquer le conducteur
        $driver->update(['is_blocked' => true]);

        // Notifier par email (une seule fois, le jour où le doc expire)
        if ($driver->email) {
            Mail::to($driver->email)->send(
                new VehicleDocumentExpiredMail($event->vehicle, $event->expiredDocs)
            );
        }
    }
}
