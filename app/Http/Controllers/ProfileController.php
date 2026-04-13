<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $vehicles = $request->user()->role === 'driver'
            ? $request->user()->vehicles()->latest()->get()
            : collect();

        $vehicle = $vehicles->first();

        return view('profile.edit', [
            'user'     => $request->user(),
            'vehicles' => $vehicles,
            'vehicle'  => $vehicle,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('success', 'Profil mis à jour avec succès.');
    }


    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ]);

        $user = $request->user();

        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return Redirect::route('profile.edit')->with('success', 'Photo de profil mise à jour.');
    }

    public function updatePhoto(Request $request, Vehicle $vehicle)
{
    $request->validate(['photo' => 'required|image|mimes:jpeg,jpg,png,webp|max:3072']);

    // Supprimer l'ancienne photo
    if ($vehicle->photo) {
        Storage::disk('public')->delete($vehicle->photo);
    }

    $vehicle->update([
        'photo' => $request->file('photo')->store('vehicles', 'public'),
    ]);

    return back()->with('success', 'Photo du véhicule mise à jour.');
}

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
