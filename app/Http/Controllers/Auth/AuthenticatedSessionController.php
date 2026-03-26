<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // ── Redirection selon le rôle ──────────────────────────
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Si l'utilisateur n'a pas de rôle (nouvel inscrit)
        if (!$user->role) {
            return redirect()->route('user.role.show');
        }

        // Si c'est un conducteur, vérifier le statut du véhicule
        if ($user->role === 'driver') {
            // Vérifier si le conducteur a un véhicule
            if ($user->vehicle) {
                // Vérifier le statut du véhicule
                switch ($user->vehicle->status) {
                    case 'pending':
                        return redirect()->route('driver.vehicle.pending')
                            ->with('info', 'Votre véhicule est en cours de vérification. Vous serez notifié dès son approbation.');
                    case 'rejected':
                        return redirect()->route('driver.vehicle.setup')
                            ->with('error', 'Votre véhicule a été rejeté. Veuillez soumettre à nouveau vos documents.');
                    case 'approved':
                        // Véhicule approuvé, rediriger vers le dashboard
                        return redirect()->intended(route('dashboard', absolute: false));
                }
            } else {
                // Conducteur sans véhicule, rediriger vers l'enregistrement
                return redirect()->route('driver.vehicle.setup')
                    ->with('warning', 'Veuillez enregistrer votre véhicule pour commencer à publier des trajets.');
            }
        }

        // Pour les passagers, redirection directe
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show the role selection page.
     */
    public function showRoleSelection(): View
    {
        // Si pas connecté → login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Admin → espace admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Rôle déjà choisi → redirection selon le rôle
        if ($user->role) {
            if ($user->role === 'driver') {
                // Vérifier le statut du véhicule
                if ($user->vehicle) {
                    switch ($user->vehicle->status) {
                        case 'pending':
                            return redirect()->route('driver.vehicle.pending');
                        case 'rejected':
                            return redirect()->route('driver.vehicle.setup');
                        case 'approved':
                            return redirect()->route('dashboard');
                    }
                } else {
                    return redirect()->route('driver.vehicle.setup');
                }
            }
            return redirect()->route('dashboard');
        }

        return view('auth.choose-role');
    }

    /**
     * Store the user's role selection.
     */
    public function storeRole(Request $request): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'in:passenger,driver'],
        ]);

        $user = Auth::user();
        $user->role = $request->role;
        $user->save();

        // Après le choix du rôle, rediriger selon le rôle
        if ($user->role === 'driver') {
            // Rediriger vers l'enregistrement du véhicule
            return redirect()->route('driver.vehicle.setup')
                ->with('success', 'Bienvenue ! Veuillez enregistrer votre véhicule pour commencer.');
        }

        // Pour les passagers, redirection directe
        return redirect()->route('dashboard')->with('success', 'Bienvenue ! Votre compte est prêt.');
    }
}
