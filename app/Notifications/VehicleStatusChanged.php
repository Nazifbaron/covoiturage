<?php

namespace App\Notifications;

use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VehicleStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Vehicle $vehicle, public string $status) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $approved = $this->status === 'approved';

        return [
            'type'       => 'vehicle_status',
            'icon'       => $approved ? 'verified' : 'cancel',
            'title'      => $approved ? 'Véhicule approuvé !' : 'Véhicule rejeté',
            'body'       => $approved
                ? "Votre véhicule {$this->vehicle->full_name} a été approuvé. Vous pouvez publier des trajets."
                : "Votre véhicule {$this->vehicle->full_name} a été rejeté. Motif : {$this->vehicle->rejection_reason}",
            'url'        => route('profile.edit'),
            'vehicle_id' => $this->vehicle->id,
        ];
    }
}
