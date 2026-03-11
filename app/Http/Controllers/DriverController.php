<?php

namespace App\Http\Controllers;
use App\Models\Pastrips;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function showCreateTips()
    {
       return view('conducteur.createTrips');
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
            ->orderBy('requested_date', 'asc')
            ->orderBy('requested_time', 'asc');

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
}
