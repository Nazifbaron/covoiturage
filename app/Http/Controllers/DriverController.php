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

class DriverController extends Controller
{
    public function showCreateTips()
    {
        $vehicle = Vehicle::where('driver_id', Auth::id())->first();

        // Pas de véhicule → redirection vers profil avec message
        if (!$vehicle) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Veuillez d\'abord enregistrer votre véhicule avant de publier un trajet.');
        }

        return view('conducteur.createTrips', compact('vehicle'));
    }


    public function storeTrip(Request $request)
    {
        // Re-vérifier côté serveur aussi
        $vehicle = Vehicle::where('driver_id', Auth::id())->first();
        abort_unless($vehicle, 422, 'Véhicule requis pour publier un trajet.');

        $validated = $request->validate([
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
        ]);
    // dd($validated);
        DriverTrips::create([
            'driver_id'         => Auth::id(),
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

        $pastrip->update([
            'status'      => 'accepted',
            'accepted_by' => Auth::id(),
        ]);

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

    // Cette partie concerne les vehicules
  public function save(Request $request)
    {
        abort_if(Auth::user()->role !== 'driver', 403);

        $validated = $request->validate([
            'type'  => ['required', 'in:moto,tricycle,voiture'],
            'brand' => ['required', 'string', 'max:80'],
            'model' => ['required', 'string', 'max:80'],
            'color' => ['required', 'string', 'max:50'],
            'plate' => ['required', 'string', 'max:20'],
        ], [
            'type.required'  => 'Le type de véhicule est obligatoire.',
            'type.in'        => 'Type invalide (moto, tricycle ou voiture).',
            'brand.required' => 'La marque est obligatoire.',
            'model.required' => 'Le modèle est obligatoire.',
            'color.required' => 'La couleur est obligatoire.',
            'plate.required' => "L'immatriculation est obligatoire.",
            'plate.max'      => "L'immatriculation ne peut pas dépasser 20 caractères.",
        ]);

        Vehicle::updateOrCreate(
            ['driver_id' => Auth::id()],
            $validated
        );

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Véhicule enregistré avec succès.');
    }

    /**
     * Supprimer le véhicule
     */
    public function destroy()
    {
        Vehicle::where('driver_id', Auth::id())->delete();

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Véhicule supprimé.');
    }

}
