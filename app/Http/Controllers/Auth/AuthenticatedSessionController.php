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

         if (! Auth::user()->role) {
            return redirect()->route('user.role.show');
        }

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

      public function showRoleSelection(): View
    {
        // Si l'user a déjà un rôle, inutile de revenir ici
        if (Auth::user()->role) {
            return redirect()->route('dashboard');
        }

        return view('auth.choose-role');
    }

     public function storeRole(Request $request): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'in:passenger,driver'],
        ]);

        $user = Auth::user();
        $user->role = $request->role;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Bienvenue ! Votre compte est prêt.');
    }
}
