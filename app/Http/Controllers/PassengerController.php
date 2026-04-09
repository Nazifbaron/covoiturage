<?php

namespace App\Http\Controllers;
use App\Models\Pastrips;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ChatMessage;
use App\Models\DriverTrips;
use App\Notifications\NewTripRequest;



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
            'driver_trip_id'    => ['nullable', 'integer', 'exists:driver_trips,id'],
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
            'vehicle_type'      => ['required', 'in:tricycle,voiture'],
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

        $pastrip = Pastrips::create([
            'user_id'           => Auth::id(),
            'driver_trip_id'    => $validated['driver_trip_id'] ?? null,
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
            'vehicle_type'      => $validated['vehicle_type'],
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

        // Notifier le conducteur si la demande est liée à un trajet spécifique
        if ($pastrip->driver_trip_id) {
            $driverTrip = DriverTrips::find($pastrip->driver_trip_id);
            if ($driverTrip?->driver) {
                try {
                    $driverTrip->driver->notify(new NewTripRequest($pastrip->load('user')));
                } catch (\Exception $e) {
                    \Log::warning('Notification NewTripRequest échouée : ' . $e->getMessage());
                }
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'chat_url' => route('passenger.chat', $pastrip->id),
            ]);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Votre demande de trajet a été publiée ! Les conducteurs aux alentours seront notifiés.');
    }


      public function availableTrips()
    {
        return view('passager.tripsavailable');
    }

    public function tripDetail(DriverTrips $trip)
    {
        abort_if($trip->status !== 'scheduled' || $trip->seats_available <= 0, 404);
        $trip->load('driver');
        return view('details', compact('trip'));
    }

   public function searchResults(Request $request)
{
    $departure  = trim($request->get('departure', ''));
    $arrival    = trim($request->get('arrival', ''));
    $date       = $request->get('date', '');
    $passengers = max(1, min(8, (int) $request->get('passengers', 1)));
    $priceMax   = $request->filled('price_max') ? max(0, (int) $request->get('price_max')) : null;
    $timeOfDay  = $request->get('time_of_day', '');
    $sortBy     = $request->get('sort', 'date');
    $luggage    = $request->boolean('luggage');
    $pets       = $request->boolean('pets');

    $query = DriverTrips::with(['driver'])
        ->where('status', 'scheduled')
        ->where('departure_date', '>=', now()->startOfDay()) // Changé ici
        ->where('seats_available', '>=', $passengers);

    // Debug - Affichez le nombre de trajets avant filtres
    \Log::info('Total trips before filters: ' . DriverTrips::where('status', 'scheduled')->count());

    if (!empty($departure)) {
        $query->where('departure_city', 'LIKE', "%{$departure}%");
        \Log::info('Filtering by departure: ' . $departure);
    }
    if (!empty($arrival)) {
        $query->where('arrival_city', 'LIKE', "%{$arrival}%");
        \Log::info('Filtering by arrival: ' . $arrival);
    }
    if (!empty($date)) {
        $query->whereDate('departure_date', $date);
        \Log::info('Filtering by date: ' . $date);
    }
    if ($priceMax !== null) {
        $query->where('price_per_seat', '<=', $priceMax);
    }
    if (in_array($timeOfDay, ['matin', 'apres-midi', 'soiree', 'nuit'])) {
        $query->where(function ($q) use ($timeOfDay) {
            match ($timeOfDay) {
                'matin'      => $q->whereTime('departure_time', '>=', '05:00')
                                  ->whereTime('departure_time', '<', '12:00'),
                'apres-midi' => $q->whereTime('departure_time', '>=', '12:00')
                                  ->whereTime('departure_time', '<', '18:00'),
                'soiree'     => $q->whereTime('departure_time', '>=', '18:00')
                                  ->whereTime('departure_time', '<', '22:00'),
                'nuit'       => $q->whereTime('departure_time', '>=', '22:00')
                                  ->orWhereTime('departure_time', '<', '05:00'),
            };
        });
    }
    if ($luggage) {
        $query->where('luggage_allowed', true);
    }
    if ($pets) {
        $query->where('pets_allowed', true);
    }

    // Debug - Affichez la requête SQL
    \Log::info('SQL Query: ' . $query->toSql());
    \Log::info('Bindings: ', $query->getBindings());

    match ($sortBy) {
        'price_asc'  => $query->orderBy('price_per_seat', 'asc')->orderBy('departure_date', 'asc'),
        'price_desc' => $query->orderBy('price_per_seat', 'desc')->orderBy('departure_date', 'asc'),
        default      => $query->orderBy('departure_date', 'asc')->orderBy('departure_time', 'asc'),
    };

    $trips = $query->paginate(8)->withQueryString();

    // Debug - Affichez le nombre de résultats
    \Log::info('Results count: ' . $trips->total());

    return view('resultat', compact(
        'trips', 'departure', 'arrival', 'date',
        'passengers', 'priceMax', 'timeOfDay', 'sortBy'
    ));
}

    // ── Endpoint JSON recherche temps réel ───────────────────────────────
     public function searchTrips(Request $request)
    {
        try {
            $dep = trim($request->get('departure', ''));
            $arr = trim($request->get('arrival',   ''));

            // Eager load driver + son véhicule
            $query = DriverTrips::with(['driver.vehicle'])
                ->where('status', 'scheduled')
                ->where('departure_date', '>=', today())
                ->where('seats_available', '>', 0)
                ->orderBy('departure_date', 'asc')
                ->orderBy('departure_time', 'asc');

            if ($dep !== '') {
                $query->where('departure_city', 'LIKE', "%{$dep}%");
            }
            if ($arr !== '') {
                $query->where('arrival_city', 'LIKE', "%{$arr}%");
            }

            $total     = $query->count();
            $paginated = $query->paginate(8);

            $trips = $paginated->map(function ($trip) {
                // Véhicule via driver (relation correcte)
                $v = optional($trip->driver)->vehicle;

                // departure_time peut être string "HH:MM:SS" ou objet Carbon
                $timeStr = is_string($trip->departure_time)
                    ? substr($trip->departure_time, 0, 5)
                    : $trip->departure_time;

                return [
                    'id'              => $trip->id,
                    'departure_city'  => $trip->departure_city,
                    'arrival_city'    => $trip->arrival_city,
                    'departure_date'  => $trip->departure_date->format('Y-m-d'),
                    'departure_time'  => $timeStr,
                    'seats_available' => (int) $trip->seats_available,
                    'seats_total'     => (int) $trip->seats_total,
                    'price_per_seat'  => (int) $trip->price_per_seat,
                    'luggage_allowed' => (bool) $trip->luggage_allowed,
                    'pets_allowed'    => (bool) $trip->pets_allowed,
                    'silent_ride'     => (bool) $trip->silent_ride,
                    'female_only'     => (bool) $trip->female_only,
                    'driver_name'     => $trip->driver
                        ? trim(($trip->driver->first_name ?? '') . ' ' . ($trip->driver->last_name ?? ''))
                        : 'Conducteur',
                    'vehicle'         => $v ? [
                        'brand'      => $v->brand,
                        'model'      => $v->model,
                        'color'      => $v->color,
                        'plate'      => $v->plate,
                        'type_icon'  => $v->type_icon,
                    ] : null,
                ];
            });

            return response()->json([
                'success'    => true,
                'trips'      => $trips->values(),
                'total'      => $total,
                'pagination' => [
                    'current_page' => $paginated->currentPage(),
                    'last_page'    => $paginated->lastPage(),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
                'trips'   => [],
                'total'   => 0,
                'pagination' => ['current_page' => 1, 'last_page' => 1],
            ], 500);
        }
    }

    public function cancelRequest(Pastrips $pastrip)
    {
        abort_unless($pastrip->user_id === Auth::id(), 403);
        abort_if($pastrip->status !== 'pending', 422, 'Seules les demandes en attente peuvent être annulées.');

        $pastrip->update(['status' => 'cancelled']);

        return redirect()->route('passenger.my-requests')
            ->with('success', 'Votre demande a été annulée.');
    }

     public function chat(Pastrips $pastrip)
    {
        // Seul le passager propriétaire peut accéder
        abort_unless($pastrip->user_id === Auth::id(), 403);

        // En attente de confirmation du conducteur → vue d'attente
        if ($pastrip->status === 'pending') {
            return view('Passager.pending', ['trip' => $pastrip]);
        }

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
