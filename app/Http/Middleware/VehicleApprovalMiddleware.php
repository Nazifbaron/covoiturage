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

        $setupRoutes = ['driver.vehicle.setup', 'driver.vehicle.store', 'driver.vehicle.pending'];

        $vehicles = $user->vehicles;

        // Aucun véhicule enregistré
        if ($vehicles->isEmpty()) {
            if (in_array($request->route()->getName(), $setupRoutes)) {
                return $next($request);
            }
            return redirect()->route('driver.vehicle.setup')
                ->with('warning', 'Vous devez d\'abord enregistrer un véhicule pour accéder au tableau de bord.');
        }

        // Au moins un véhicule approuvé → accès autorisé
        if ($vehicles->where('status', 'approved')->isNotEmpty()) {
            return $next($request);
        }

        // Au moins un en attente (mais aucun approuvé)
        if ($vehicles->where('status', 'pending')->isNotEmpty()) {
            if ($request->route()->getName() === 'driver.vehicle.pending') {
                return $next($request);
            }
            return redirect()->route('driver.vehicle.pending')
                ->with('info', 'Votre véhicule est en cours de vérification par nos administrateurs.');
        }

        // Tous rejetés
        if (in_array($request->route()->getName(), $setupRoutes)) {
            return $next($request);
        }
        $rejected = $vehicles->firstWhere('status', 'rejected');
        return redirect()->route('driver.vehicle.setup')
            ->with('error', 'Votre véhicule a été rejeté. ' . ($rejected?->rejection_reason ? 'Raison : ' . $rejected->rejection_reason : 'Veuillez en soumettre un nouveau.'));
    }
}
