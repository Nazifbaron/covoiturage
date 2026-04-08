<?php

namespace App\Http\Controllers;
use App\Models\Pastrips;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Vehicle;
use App\Models\DriverTrips;


use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\NewVehicleSubmitted;
use App\Notifications\TripRequestAccepted;

class DriverController extends Controller
{
    public function showCreateTips()
    {
        $vehicles = Vehicle::where('driver_id', Auth::id())
                            ->where('status', 'approved')
                            ->get();

        if ($vehicles->isEmpty()) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Vous devez avoir au moins un véhicule approuvé pour publier un trajet.');
        }

        $vehicle = $vehicles->first();

        return view('conducteur.createTrips', compact('vehicles', 'vehicle'));
    }


    public function storeTrip(Request $request)
    {
        // Re-vérifier côté serveur aussi
        $vehicle = Vehicle::where('driver_id', Auth::id())->first();
        abort_unless($vehicle, 422, 'Véhicule requis pour publier un trajet.');

        $validated = $request->validate([
            'vehicle_id'        => ['required', 'integer', 'exists:vehicles,id'],
            'departure_city'    => ['required', 'string', 'max:100'],
            'arrival_city'      => ['required', 'string', 'max:100'],
            'departure_address' => ['nullable', 'string', 'max:255'],
            'arrival_address'   => ['nullable', 'string', 'max:255'],
            'departure_lat'     => ['nullable', 'numeric', 'between:-90,90'],
            'departure_lng'     => ['nullable', 'numeric', 'between:-180,180'],
            'arrival_lat'       => ['nullable', 'numeric', 'between:-90,90'],
            'arrival_lng'       => ['nullable', 'numeric', 'between:-180,180'],
            'departure_date'    => ['required', 'date', 'after_or_equal:today'],
            'departure_time'    => ['required', 'date_format:H:i'],
            'seats_total'       => ['required', 'integer', 'min:1', 'max:7'],
            'price_per_seat'    => ['required', 'integer', 'min:0'],
            'luggage_allowed'   => ['nullable', 'boolean'],
            'pets_allowed'      => ['nullable', 'boolean'],
            'silent_ride'       => ['nullable', 'boolean'],
            'female_only'       => ['nullable', 'boolean'],
            'description'       => ['nullable', 'string', 'max:500'],
        ], [
            'departure_city.required'    => 'La ville de départ est obligatoire.',
            'arrival_city.required'      => 'La ville d\'arrivée est obligatoire.',
            'departure_date.required'    => 'La date de départ est obligatoire.',
            'departure_date.after_or_equal' => 'La date doit être aujourd\'hui ou dans le futur.',
            'departure_time.required'    => 'L\'heure de départ est obligatoire.',
            'seats_total.required'       => 'Le nombre de places est obligatoire.',
            'seats_total.min'            => 'Au moins 1 place requise.',
            'seats_total.max'            => '7 places maximum.',
            'price_per_seat.required'    => 'Le prix par siège est obligatoire.',
            'price_per_seat.min'         => 'Le prix ne peut pas être négatif.',
            'vehicle_id.required'        => 'Veuillez sélectionner un véhicule.',
            'vehicle_id.exists'          => 'Véhicule invalide.',
        ]);

        // Vérifier que le véhicule appartient bien au conducteur et est approuvé
        $vehicle = Vehicle::where('id', $validated['vehicle_id'])
                          ->where('driver_id', Auth::id())
                          ->where('status', 'approved')
                          ->firstOrFail();

        DriverTrips::create([
            'driver_id'         => Auth::id(),
            'vehicle_id'        => $vehicle->id,
            'departure_city'    => $validated['departure_city'],
            'arrival_city'      => $validated['arrival_city'],
            'departure_address' => $validated['departure_address'] ?? null,
            'arrival_address'   => $validated['arrival_address']   ?? null,
            'departure_lat'     => $validated['departure_lat']     ?? null,
            'departure_lng'     => $validated['departure_lng']     ?? null,
            'arrival_lat'       => $validated['arrival_lat']       ?? null,
            'arrival_lng'       => $validated['arrival_lng']       ?? null,
            'departure_date'    => $validated['departure_date'],
            'departure_time'    => $validated['departure_time'],
            'seats_total'       => (int) $validated['seats_total'],
            'seats_available'   => (int) $validated['seats_total'], // dispo = total au départ
            'price_per_seat'    => (int) $validated['price_per_seat'],
            'luggage_allowed'   => $request->boolean('luggage_allowed'),
            'pets_allowed'      => $request->boolean('pets_allowed'),
            'silent_ride'       => $request->boolean('silent_ride'),
            'female_only'       => $request->boolean('female_only'),
            'description'       => $validated['description'] ?? null,
            'status'            => 'scheduled',
        ]);

        return redirect()->route('driver.my-trips')
            ->with('success', 'Votre trajet a été publié ! Les passagers peuvent maintenant le trouver.');
    }

    public function myTrips()
    {
        $counts = [
            'total'     => DriverTrips::where('driver_id', Auth::id())->count(),
            'scheduled' => DriverTrips::where('driver_id', Auth::id())->where('status', 'scheduled')->count(),
            'completed' => DriverTrips::where('driver_id', Auth::id())->where('status', 'completed')->count(),
            'cancelled' => DriverTrips::where('driver_id', Auth::id())->where('status', 'cancelled')->count(),
        ];

        $trips = DriverTrips::where('driver_id', Auth::id())
            ->orderBy('departure_date', 'desc')
            ->orderBy('departure_time', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('conducteur.mytrips', compact('trips', 'counts'));
    }

     public function cancelTrip(DriverTrips $trip)
    {
        abort_unless((int) $trip->driver_id === (int) Auth::id(), 403);
        abort_if($trip->status === 'completed', 422, 'Impossible d\'annuler un trajet terminé.');

        $trip->update(['status' => 'cancelled']);

        return redirect()->route('driver.my-trips')
            ->with('success', 'Trajet annulé.');
    }
    public function requests(Request $request)
    {
        // Expirer automatiquement les demandes périmées
        Pastrips::where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        $query = Pastrips::with('user')
            ->whereIn('status', ['pending', 'accepted'])
            ->orderBy('requested_date', 'desc')
            ->orderBy('requested_time', 'desc');

        // Filtre date
        switch ($request->get('date_filter', '')) {
            case 'today':
                $query->whereDate('requested_date', today());
                break;
            case 'tomorrow':
                $query->whereDate('requested_date', today()->addDay());
                break;
            case 'this_week':
                $query->whereBetween('requested_date', [today(), today()->addDays(6)]);
                break;
        }

        $pastrips = $query->paginate(10)->withQueryString();

        return view('conducteur.demande', compact('pastrips'));
    }

      public function acceptRequest(Pastrips $pastrip)
    {
        // Vérifications
        abort_if($pastrip->status !== 'pending', 422, 'Cette demande n\'est plus disponible.');
        abort_if(Auth::user()->role !== 'driver', 403);

        // Si la demande est liée à un trajet spécifique, vérifier qu'il appartient au conducteur connecté
        if ($pastrip->driver_trip_id) {
            $driverTrip = DriverTrips::find($pastrip->driver_trip_id);
            abort_unless(
                $driverTrip && (int) $driverTrip->driver_id === (int) Auth::id(),
                403,
                'Cette demande est liée à un trajet qui ne vous appartient pas.'
            );
        }

        $pastrip->update([
            'status'      => 'accepted',
            'accepted_by' => Auth::id(),
        ]);

        // Décrémenter les places disponibles sur le trajet conducteur lié
        if ($pastrip->driver_trip_id) {
            $driverTrip = $driverTrip ?? DriverTrips::find($pastrip->driver_trip_id);
            if ($driverTrip) {
                $newSeats = max(0, $driverTrip->seats_available - $pastrip->passengers);
                // Un trajet complet reste 'scheduled' : complet ≠ terminé
                $driverTrip->update([
                    'seats_available' => $newSeats,
                ]);
            }
        }

        // Notifier le passager
        try {
            $pastrip->user->notify(new TripRequestAccepted($pastrip));
        } catch (\Exception $e) {
            \Log::warning('Notification TripRequestAccepted échouée : ' . $e->getMessage());
        }

        return redirect()
            ->route('driver.chat', $pastrip->id)
            ->with('success', 'Course acceptée ! Contactez le passager pour vous coordonner.');
    }

    public function chat(Pastrips $pastrip)
    {
        // Seul le conducteur acceptant ou le passager peut accéder
        $this->authorizeChatAccess($pastrip);

        // Déterminer qui est le passager
        $passenger = User::findOrFail($pastrip->user_id);

        // Charger les 60 derniers messages
        $messages = ChatMessage::where('trip_id', $pastrip->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->take(60)
            ->get();

        // Marquer les messages reçus comme lus
        ChatMessage::where('trip_id', $pastrip->id)
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('conducteur.chat', [
            'trip'      => $pastrip,
            'passenger' => $passenger,
            'messages'  => $messages,
        ]);
    }
                 public function pollMessages(Request $request, Pastrips $pastrip)
    {
        $this->authorizeChatAccess($pastrip);

        $after = (int) $request->get('after', 0);

        $messages = ChatMessage::where('trip_id', $pastrip->id)
            ->where('id', '>', $after)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($m) => [
                'id'         => $m->id,
                'sender_id'  => $m->sender_id,
                'content'    => $m->content,
                'created_at' => $m->created_at->toISOString(),
                'read_at'    => $m->read_at,
            ]);

        if ($messages->isNotEmpty()) {
            ChatMessage::where('trip_id', $pastrip->id)
                ->where('sender_id', '!=', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        $otherUserId = Auth::id() === $pastrip->user_id
            ? $pastrip->accepted_by
            : $pastrip->user_id;
        $isTyping = Cache::has("typing:{$pastrip->id}:{$otherUserId}");

        return response()->json([
            'messages'  => $messages,
            'is_typing' => $isTyping,
        ]);
    }

    public function sendMessage(Request $request, Pastrips $pastrip)
    {
        $this->authorizeChatAccess($pastrip);

        $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $message = ChatMessage::create([
            'trip_id'   => $pastrip->id,
            'sender_id' => Auth::id(),
            'content'   => $request->input('content'),
        ]);

        Cache::forget("typing:{$pastrip->id}:" . Auth::id());

        return response()->json([
            'message' => [
                'id'         => $message->id,
                'sender_id'  => $message->sender_id,
                'content'    => $message->content,
                'created_at' => $message->created_at->toISOString(),
                'read_at'    => null,
            ],
        ]);
    }

    public function markRead(Pastrips $pastrip)
    {
        $this->authorizeChatAccess($pastrip);

        ChatMessage::where('trip_id', $pastrip->id)
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function typingStart(Pastrips $pastrip)
    {
        $this->authorizeChatAccess($pastrip);
        Cache::put("typing:{$pastrip->id}:" . Auth::id(), true, now()->addSeconds(4));
        return response()->json(['ok' => true]);
    }

    public function typingStop(Pastrips $pastrip)
    {
        $this->authorizeChatAccess($pastrip);
        Cache::forget("typing:{$pastrip->id}:" . Auth::id());
        return response()->json(['ok' => true]);
    }


      private function authorizeChatAccess(Pastrips $pastrip): void
    {
        $userId = Auth::id();
        abort_unless(
            $pastrip->user_id === $userId || $pastrip->accepted_by === $userId,
            403,
            'Accès non autorisé à cette conversation.'
        );
        abort_if(
            $pastrip->status !== 'accepted',
            403,
            'La course doit être acceptée pour accéder au chat.'
        );
    }

    // ── Gains ──────────────────────────────────────────────────────────────

    public function earnings(Request $request)
    {
        $driverId = Auth::id();

        // Tous les trajets du conducteur (hors annulés) avec au moins 1 place vendue
        $allTrips = DriverTrips::where('driver_id', $driverId)
            ->where('status', '!=', 'cancelled')
            ->get();

        // Calcul : gain = price_per_seat × places vendues (seats_total - seats_available)
        $earning = fn(DriverTrips $t) => $t->price_per_seat * ($t->seats_total - $t->seats_available);

        $totalEarnings    = $allTrips->sum($earning);
        $totalPassengers  = $allTrips->sum(fn($t) => $t->seats_total - $t->seats_available);
        $totalTrips       = $allTrips->count();

        $thisMonth = $allTrips
            ->filter(fn($t) => $t->departure_date->isCurrentMonth())
            ->sum($earning);

        $lastMonth = $allTrips
            ->filter(fn($t) => $t->departure_date->month === now()->subMonth()->month
                             && $t->departure_date->year  === now()->subMonth()->year)
            ->sum($earning);

        // Évolution vs mois dernier (%)
        $evolution = $lastMonth > 0
            ? round((($thisMonth - $lastMonth) / $lastMonth) * 100)
            : ($thisMonth > 0 ? 100 : 0);

        // Répartition mensuelle sur les 6 derniers mois
        $monthly = collect(range(5, 0))->map(function ($monthsAgo) use ($allTrips, $earning) {
            $ref = now()->subMonths($monthsAgo);
            $gains = $allTrips
                ->filter(fn($t) => $t->departure_date->month === (int) $ref->month
                                 && $t->departure_date->year  === (int) $ref->year)
                ->sum($earning);
            return [
                'label'  => $ref->locale('fr')->isoFormat('MMM YY'),
                'amount' => $gains,
            ];
        });

        // Trajets avec gains > 0, triés par date décroissante (paginés)
        $trips = DriverTrips::where('driver_id', $driverId)
            ->where('status', '!=', 'cancelled')
            ->whereColumn('seats_available', '<', 'seats_total')
            ->orderBy('departure_date', 'desc')
            ->orderBy('departure_time', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Meilleur trajet
        $bestTrip = $allTrips->sortByDesc($earning)->first();

        return view('conducteur.earnings', compact(
            'totalEarnings', 'totalPassengers', 'totalTrips',
            'thisMonth', 'lastMonth', 'evolution',
            'monthly', 'trips', 'bestTrip'
        ));
    }

    // ── Véhicules ──────────────────────────────────────────────────────────

    public function showVehicleSetup()
    {
        $count = Auth::user()->vehicles()->count();

        if ($count >= 3) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Vous avez atteint la limite de 3 véhicules.');
        }

        return view('auth.vehicle-setup');
    }

    public function pending()
    {
        $user = Auth::user();
        $pendingVehicle = $user->vehicles()->where('status', 'pending')->latest()->first();

        if (!$pendingVehicle) {
            return redirect()->route('driver.vehicle.setup');
        }

        return view('auth.vehicle-pending', ['vehicle' => $pendingVehicle]);
    }

    public function storeVehicle(Request $request)
    {
        // ── 1. Limite 3 véhicules ──────────────────────────────────────────
        if (Auth::user()->vehicles()->count() >= 3) {
            return back()->withErrors(['general' => 'Vous avez atteint la limite de 3 véhicules.']);
        }

        // ── 2. Validation ─────────────────────────────────────────────────
        $validated = $request->validate([
            'type'              => ['required', Rule::in(['moto', 'tricycle', 'voiture'])],
            'brand'             => ['required', 'string', 'max:80'],
            'model'             => ['required', 'string', 'max:80'],
            'color'             => ['required', 'string', 'max:50'],
            'plate'             => ['required', 'string', 'max:20', 'unique:vehicles,plate'],
            'insurance'         => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'registration'      => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'technical_control' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'driver_license'    => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'type.required'              => 'Veuillez sélectionner un type de véhicule.',
            'type.in'                    => 'Type de véhicule invalide.',
            'brand.required'             => 'Veuillez entrer la marque du véhicule.',
            'brand.max'                  => 'La marque ne doit pas dépasser 80 caractères.',
            'model.required'             => 'Veuillez entrer le modèle du véhicule.',
            'color.required'             => 'Veuillez entrer la couleur du véhicule.',
            'plate.required'             => "Veuillez entrer l'immatriculation.",
            'plate.unique'               => 'Cette immatriculation est déjà enregistrée.',
            'insurance.required'         => "L'assurance véhicule est requise.",
            'insurance.mimes'            => "L'assurance doit être un fichier PDF, JPG ou PNG.",
            'insurance.max'              => "L'assurance ne doit pas dépasser 5 Mo.",
            'registration.required'      => 'La carte grise est requise.',
            'registration.mimes'         => 'La carte grise doit être un fichier PDF, JPG ou PNG.',
            'registration.max'           => 'La carte grise ne doit pas dépasser 5 Mo.',
            'technical_control.required' => 'Le contrôle technique est requis.',
            'technical_control.mimes'    => 'Le contrôle technique doit être un fichier PDF, JPG ou PNG.',
            'technical_control.max'      => 'Le contrôle technique ne doit pas dépasser 5 Mo.',
            'driver_license.required'    => 'Le permis de conduire est requis.',
            'driver_license.mimes'       => 'Le permis de conduire doit être un fichier PDF, JPG ou PNG.',
            'driver_license.max'         => 'Le permis de conduire ne doit pas dépasser 5 Mo.',
        ]);

        // ── 3. Stockage des fichiers ──────────────────────────────────────
        $driverFolder = 'vehicles/driver_' . Auth::id();
        $docs  = ['insurance', 'registration', 'technical_control', 'driver_license'];
        $paths = [];

        foreach ($docs as $doc) {
            $file        = $request->file($doc);
            $path        = $file->store("{$driverFolder}/{$doc}", 'public');
            $paths[$doc] = ['path' => $path, 'name' => $file->getClientOriginalName()];
        }

        // ── 4. Création ───────────────────────────────────────────────────
        $vehicle = Vehicle::create([
            'driver_id'              => Auth::id(),
            'type'                   => $validated['type'],
            'brand'                  => $validated['brand'],
            'model'                  => $validated['model'],
            'color'                  => $validated['color'],
            'plate'                  => strtoupper($validated['plate']),
            'status'                 => 'pending',
            'insurance_path'         => $paths['insurance']['path'],
            'insurance_name'         => $paths['insurance']['name'],
            'registration_path'      => $paths['registration']['path'],
            'registration_name'      => $paths['registration']['name'],
            'technical_control_path' => $paths['technical_control']['path'],
            'technical_control_name' => $paths['technical_control']['name'],
            'driver_license_path'    => $paths['driver_license']['path'],
            'driver_license_name'    => $paths['driver_license']['name'],
        ]);

        // ── 5. Notifier l'admin ───────────────────────────────────────────
        try {
            Mail::to(config('app.admin_email'))
                ->send(new NewVehicleSubmitted($vehicle->load('driver')));
        } catch (\Exception $e) {
            \Log::error('Échec envoi mail admin véhicule : ' . $e->getMessage());
        }

        return redirect()->route('driver.vehicle.pending')
            ->with('success', 'Véhicule soumis avec succès. Il sera visible dès validation par l\'administrateur.');
    }

    public function destroy(Vehicle $vehicle)
    {
        abort_unless((int) $vehicle->driver_id === (int) Auth::id(), 403);
        abort_if($vehicle->status === 'approved', 422, 'Un véhicule approuvé ne peut pas être supprimé.');

        $vehicle->delete();

        return redirect()->route('profile.edit')
            ->with('success', 'Véhicule supprimé.');
    }

}
