<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Model;

class Pastrips extends Model
{
    protected $fillable = [
        'user_id',
        'departure_city',
        'arrival_city',
        'departure_address',
        'arrival_address',
        'departure_lat',
        'departure_lng',
        'arrival_lat',
        'arrival_lng',
        'requested_date',
        'requested_time',
        'flexibility',
        'passengers',
        'budget_max',
        'need_luggage_space',
        'female_driver_only',
        'pets_with_me',
        'silent_ride',
        'message',
        'expires_in_hours',
        'expires_at',
        'status',
        'accepted_by',
    ];

    protected $casts = [
        'requested_date'     => 'date',
        'expires_at'         => 'datetime',
        'need_luggage_space' => 'boolean',
        'female_driver_only' => 'boolean',
        'pets_with_me'       => 'boolean',
        'silent_ride'        => 'boolean',
        'departure_lat'      => 'float',
        'departure_lng'      => 'float',
        'arrival_lat'        => 'float',
        'arrival_lng'        => 'float',
    ];

    // Passager qui a créé la demande
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Conducteur qui a accepté la course
    public function driver()
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }
}
