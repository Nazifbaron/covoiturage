<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'driver_id',
        'type',
        'brand',
        'model',
        'color',
        'plate',
    ];

    protected $casts = [
        'driver_id' => 'integer',
    ];

    // Labels lisibles
    public static array $types = [
        'moto'     => ['label' => 'Moto',      'icon' => 'two_wheeler'],
        'tricycle' => ['label' => 'Tricycle',   'icon' => 'electric_rickshaw'],
        'voiture'  => ['label' => 'Voiture',    'icon' => 'directions_car'],
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // Nom complet du véhicule
    public function getFullNameAttribute(): string
    {
        return "{$this->brand} {$this->model}";
    }

    // Label du type
    public function getTypeLabelAttribute(): string
    {
        return self::$types[$this->type]['label'] ?? $this->type;
    }

    // Icône Material Symbol du type
    public function getTypeIconAttribute(): string
    {
        return self::$types[$this->type]['icon'] ?? 'directions_car';
    }
}
