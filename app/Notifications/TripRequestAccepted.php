<?php

namespace App\Notifications;

use App\Models\Pastrips;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TripRequestAccepted extends Notification
{
    use Queueable;

    public function __construct(public Pastrips $pastrip) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'trip_request_accepted',
            'icon'       => 'check_circle',
            'title'      => 'Demande acceptée !',
            'body'       => "Votre trajet {$this->pastrip->departure_city} → {$this->pastrip->arrival_city} a été accepté.",
            'url'        => route('passenger.chat', $this->pastrip->id),
            'pastrip_id' => $this->pastrip->id,
        ];
    }
}
