<?php

namespace App\Http\Controllers;
use App\Models\Pastrips;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class PassengerController extends Controller
{
    public function showCreateRequest()
    {
       return view('passager.add-trips');
    }

    public function showMyRequests(Request $request)
    {
        // Expiration automatique : pending → expired si expires_at dépassé
        Pastrips::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        $query = Pastrips::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Filtre par statut si présent dans l'URL (?status=pending)
        $allowedStatuses = ['pending', 'accepted', 'cancelled', 'expired'];
        if ($request->filled('status') && in_array($request->status, $allowedStatuses)) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(10)->withQueryString();

        return view('Passager.Request', compact('requests'));
    }

    public function storetrips(Request $request)
    {
        $validated = $request->validate([
            'departure_city'    => ['required', 'string', 'max:100'],
            'arrival_city'      => ['required', 'string', 'max:100'],
            'departure_address' => ['nullable', 'string', 'max:255'],
            'arrival_address'   => ['nullable', 'string', 'max:255'],

            // Coordonnées GPS envoyées par Leaflet/Nominatim
            'departure_lat'     => ['nullable', 'numeric', 'between:-90,90'],
            'departure_lng'     => ['nullable', 'numeric', 'between:-180,180'],
            'arrival_lat'       => ['nullable', 'numeric', 'between:-90,90'],
            'arrival_lng'       => ['nullable', 'numeric', 'between:-180,180'],

            'requested_date'    => ['required', 'date', 'after_or_equal:today'],
            'requested_time'    => ['required', 'date_format:H:i'],
            'flexibility'       => ['required', 'in:0,30,60,120'],
            'passengers'        => ['required', 'integer', 'min:1', 'max:4'],
            'budget_max'        => ['nullable', 'integer', 'min:0'],
            'need_luggage_space'=> ['nullable', 'boolean'],
            'female_driver_only'=> ['nullable', 'boolean'],
            'pets_with_me'      => ['nullable', 'boolean'],
            'silent_ride'       => ['nullable', 'boolean'],
            'message'           => ['nullable', 'string', 'max:500'],
            'expires_in_hours'  => ['required', 'in:1,3,6,24'],
        ], [
            'departure_city.required'       => 'La ville de départ est obligatoire.',
            'arrival_city.required'         => 'La ville d\'arrivée est obligatoire.',
            'requested_date.required'       => 'La date du trajet est obligatoire.',
            'requested_date.after_or_equal' => 'La date doit être aujourd\'hui ou dans le futur.',
            'requested_time.required'       => 'L\'heure de départ est obligatoire.',
            'requested_time.date_format'    => 'Format d\'heure invalide.',
            'flexibility.in'                => 'Flexibilité invalide.',
            'passengers.required'           => 'Le nombre de passagers est obligatoire.',
            'passengers.min'                => 'Au moins 1 passager requis.',
            'passengers.max'                => '4 passagers maximum.',
            'budget_max.min'                => 'Le budget ne peut pas être négatif.',
            'expires_in_hours.in'           => 'Durée de validité invalide.',
            'departure_lat.between'         => 'Coordonnées de départ invalides.',
            'arrival_lat.between'           => 'Coordonnées d\'arrivée invalides.',
        ]);

        $expiresAt = now()->addHours((int) $validated['expires_in_hours']);
    // dd($validated, $expiresAt);
        Pastrips::create([
            'user_id'           => Auth::id(),
            'departure_city'    => $validated['departure_city'],
            'arrival_city'      => $validated['arrival_city'],
            'departure_address' => $validated['departure_address'] ?? null,
            'arrival_address'   => $validated['arrival_address'] ?? null,

            // GPS
            'departure_lat'     => $validated['departure_lat'] ?? null,
            'departure_lng'     => $validated['departure_lng'] ?? null,
            'arrival_lat'       => $validated['arrival_lat']   ?? null,
            'arrival_lng'       => $validated['arrival_lng']   ?? null,

            'requested_date'    => $validated['requested_date'],
            'requested_time'    => $validated['requested_time'],
            'flexibility'       => (int) $validated['flexibility'],
            'passengers'        => (int) $validated['passengers'],
            'budget_max'        => isset($validated['budget_max']) ? (int) $validated['budget_max'] : null,
            'need_luggage_space'=> $request->boolean('need_luggage_space'),
            'female_driver_only'=> $request->boolean('female_driver_only'),
            'pets_with_me'      => $request->boolean('pets_with_me'),
            'silent_ride'       => $request->boolean('silent_ride'),
            'message'           => $validated['message'] ?? null,
            'expires_in_hours'  => (int) $validated['expires_in_hours'],
            'expires_at'        => $expiresAt,
            'status'            => 'pending',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Votre demande de trajet a été publiée ! Les conducteurs aux alentours seront notifiés.');
    }
}
