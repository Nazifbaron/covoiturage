<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VehicleApprovalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Si l'utilisateur n'est pas connecté
        if (!$user) {
            return redirect()->route('login');
        }

        // Si l'utilisateur n'est pas conducteur, laisser passer
        if ($user->role !== 'driver') {
            return $next($request);
        }

        // Vérifier si l'utilisateur a un véhicule
        $vehicle = $user->vehicle;

        // Pas de véhicule enregistré
        if (!$vehicle) {
            // Si la route actuelle est déjà celle d'enregistrement, laisser passer
            if ($request->route()->getName() === 'driver.vehicle.setup' ||
                $request->route()->getName() === 'driver.vehicle.store') {
                return $next($request);
            }

            return redirect()->route('driver.vehicle.setup')
                ->with('warning', 'Vous devez d\'abord enregistrer votre véhicule pour accéder au tableau de bord.');
        }

        // Véhicule en attente d'approbation
        if ($vehicle->status === 'pending') {
            // Si la route est celle de la page d'attente, laisser passer
            if ($request->route()->getName() === 'driver.vehicle.pending') {
                return $next($request);
            }

            return redirect()->route('driver.vehicle.pending')
                ->with('info', 'Votre véhicule est en cours de vérification par nos administrateurs.');
        }

        // Véhicule rejeté
        if ($vehicle->status === 'rejected') {
            // Si la route est celle de modification, laisser passer
            if ($request->route()->getName() === 'driver.vehicle.setup' ||
                $request->route()->getName() === 'driver.vehicle.store') {
                return $next($request);
            }

            return redirect()->route('driver.vehicle.setup')
                ->with('error', 'Votre véhicule a été rejeté. Raison: ' . ($vehicle->rejection_reason ?? 'Veuillez soumettre à nouveau vos documents.'));
        }

        // Véhicule approuvé - tout est bon, laisser passer
        return $next($request);
    }
}
