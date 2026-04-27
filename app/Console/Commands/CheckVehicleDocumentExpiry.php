<?php

namespace App\Console\Commands;

use App\Events\VehicleDocumentExpired;
use App\Events\VehicleDocumentExpiringSoon;
use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CheckVehicleDocumentExpiry extends Command
{
    protected $signature   = 'vehicles:check-document-expiry';
    protected $description = 'Vérifie les pièces de véhicule qui expirent bientôt et bloque les conducteurs dont les docs sont expirés';

    // Notifications envoyées seulement à ces jalons pour éviter le spam
    private array $warningDays = [30, 14, 7, 1, 0];

    private array $documents = [
        ['label' => 'Assurance',   'field' => 'insurance_expires_at'],
        ['label' => 'Carte grise', 'field' => 'registration_expires_at'],
    ];

    public function handle(): int
    {
        $today    = Carbon::today();
        $warned   = 0;
        $blocked  = 0;

        Vehicle::with('driver')
            ->where('status', 'approved')
            ->whereNotNull('driver_id')
            ->get()
            ->each(function (Vehicle $vehicle) use ($today, &$warned, &$blocked) {
                $expiringDocs = [];
                $expiredDocs  = [];

                foreach ($this->documents as $doc) {
                    $expiresAt = $vehicle->{$doc['field']};
                    if (!$expiresAt) {
                        continue;
                    }

                    // Jours avant expiration : positif = futur, négatif = déjà expiré
                    $daysRemaining = (int) $today->diffInDays($expiresAt, false);

                    if ($daysRemaining < 0) {
                        $expiredDocs[] = [
                            'label'       => $doc['label'],
                            'expires_at'  => $expiresAt,
                            'days_expired' => abs($daysRemaining),
                        ];
                    } elseif (in_array($daysRemaining, $this->warningDays)) {
                        $expiringDocs[] = [
                            'label'          => $doc['label'],
                            'expires_at'     => $expiresAt,
                            'days_remaining' => $daysRemaining,
                        ];
                    }
                }

                // Documents expirés → bloquer le conducteur (seulement s'il ne l'est pas déjà)
                if (!empty($expiredDocs) && $vehicle->driver && !$vehicle->driver->is_blocked) {
                    VehicleDocumentExpired::dispatch($vehicle, $expiredDocs);
                    $blocked++;

                    $this->warn("  ✗ Conducteur #{$vehicle->driver_id} BLOQUÉ ("
                        . collect($expiredDocs)->pluck('label')->join(', ') . ')');
                }

                // Documents qui expirent bientôt → avertir
                if (!empty($expiringDocs)) {
                    VehicleDocumentExpiringSoon::dispatch($vehicle, $expiringDocs);
                    $warned++;

                    $this->line("  → Conducteur #{$vehicle->driver_id} averti ("
                        . collect($expiringDocs)->pluck('label')->join(', ') . ')');
                }
            });

        $this->info("Terminé : {$warned} averti(s), {$blocked} bloqué(s).");

        return self::SUCCESS;
    }
}
