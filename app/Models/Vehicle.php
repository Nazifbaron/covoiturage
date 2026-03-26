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
        'status',
        'rejection_reason',
        'approved_at',
        'insurance_path',
        'insurance_name',
        'registration_path',
        'registration_name',
        'technical_control_path',
        'technical_control_name',
        'driver_license_path',
        'driver_license_name'
    ];

    protected $casts = [
        'driver_id' => 'integer',
    ];

    // Labels lisibles
    public static array $types = [
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

    // Vérifier si le véhicule est approuvé
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    // Vérifier si le véhicule est en attente
    public function isPending()
    {
        return $this->status === 'pending';
    }

    // Vérifier si le véhicule est rejeté
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    // Icône Material Symbol du type
    public function getTypeIconAttribute(): string
    {
        return self::$types[$this->type]['icon'] ?? 'directions_car';
    }
}
