<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Model;

class Pastrips extends Model
{
      use HasFactory;

    protected $fillable = [
        'user_id',
        'departure_city',
        'arrival_city',
        'departure_address',
        'arrival_address',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

