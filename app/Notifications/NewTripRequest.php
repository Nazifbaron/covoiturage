<?php

namespace App\Notifications;

use App\Models\Pastrips;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewTripRequest extends Notification
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
            'type'       => 'new_trip_request',
            'icon'       => 'hail',
            'title'      => 'Nouvelle demande de trajet',
            'body'       => "{$this->pastrip->user->first_name} demande un trajet {$this->pastrip->departure_city} → {$this->pastrip->arrival_city}",
            'url'        => route('driver.requests'),
            'pastrip_id' => $this->pastrip->id,
        ];
    }
}
