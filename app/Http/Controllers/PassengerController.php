<?php

namespace App\Http\Controllers;
use App\Models\Pastrips;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ChatMessage;



use Illuminate\Http\Request;

class PassengerController extends Controller
{
    public function showCreateRequest()
    {
       return view('passager.add-trips');
    }

     public function showMyRequests(Request $request)
    {
        $userId = Auth::id();

        // Expiration automatique
        Pastrips::where('user_id', $userId)
            ->where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        // Comptages réels (toutes pages confondues, indépendants du filtre actif)
        $counts = [
            'total'     => Pastrips::where('user_id', $userId)->count(),
            'pending'   => Pastrips::where('user_id', $userId)->where('status', 'pending')->count(),
            'accepted'  => Pastrips::where('user_id', $userId)->where('status', 'accepted')->count(),
            'cancelled' => Pastrips::where('user_id', $userId)->where('status', 'cancelled')->count(),
            'expired'   => Pastrips::where('user_id', $userId)->where('status', 'expired')->count(),
        ];

        // Requête avec filtre optionnel + eager load du conducteur
        $query = Pastrips::where('user_id', $userId)
            ->with('driver')       // relation sur accepted_by
            ->orderBy('created_at', 'desc');

        $allowedStatuses = ['pending', 'accepted', 'cancelled', 'expired'];
        if ($request->filled('status') && in_array($request->status, $allowedStatuses)) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(10)->withQueryString();

        return view('Passager.Request', compact('requests', 'counts'));
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

     public function chat(Pastrips $pastrip)
    {
        // Seul le passager propriétaire peut accéder
        abort_unless($pastrip->user_id === Auth::id(), 403);
        abort_if($pastrip->status !== 'accepted', 403, 'La course doit être acceptée.');

        $driver = User::findOrFail($pastrip->accepted_by);

        $messages = ChatMessage::where('trip_id', $pastrip->id)
            ->orderBy('created_at', 'asc')
            ->take(60)
            ->get();

        // Marquer les messages du conducteur comme lus
        ChatMessage::where('trip_id', $pastrip->id)
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('passager.chat', [
            'trip'      => $pastrip,
            'passenger' => $driver,   // variable $passenger = l'interlocuteur affiché dans la vue
            'messages'  => $messages,
            'isDriver'  => false,
        ]);
    }
}
