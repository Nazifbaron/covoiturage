<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverTrips extends Model
{

protected $table = 'driver_trips';

    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'departure_city',
        'arrival_city',
        'departure_address',
        'arrival_address',
        'departure_lat',
        'departure_lng',
        'arrival_lat',
        'arrival_lng',
        'departure_date',
        'departure_time',
        'seats_total',
        'seats_available',
        'price_per_seat',
        'luggage_allowed',
        'pets_allowed',
        'silent_ride',
        'female_only',
        'description',
        'status',
    ];

    protected $casts = [
        'driver_id'       => 'integer',
        'departure_date'  => 'date',
        'seats_total'     => 'integer',
        'seats_available' => 'integer',
        'price_per_seat'  => 'integer',
        'luggage_allowed' => 'boolean',
        'pets_allowed'    => 'boolean',
        'silent_ride'     => 'boolean',
        'female_only'     => 'boolean',
        'departure_lat'   => 'float',
        'departure_lng'   => 'float',
        'arrival_lat'     => 'float',
        'arrival_lng'     => 'float',
    ];
   public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // Véhicule choisi par le conducteur pour ce trajet
    public function vehicle()
    {
        return $this->belongsTo(\App\Models\Vehicle::class, 'vehicle_id');
    }

    // Vérifie si le trajet est complet
    public function isFull(): bool
    {
        return $this->seats_available <= 0;
    }
}
