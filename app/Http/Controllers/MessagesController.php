<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Pastrips;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MessagesController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Tous les trip_id où l'utilisateur a participé (conducteur ou passager)
        $tripIds = Pastrips::where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhere('accepted_by', $userId);
            })
            ->pluck('id');

        // si la table pastrips contient des trajets, on peut aussi filtrer sur messages existants pour éviter les chemins sans conversation
        $tripIds = ChatMessage::whereIn('trip_id', $tripIds)
            ->distinct()
            ->pluck('trip_id');

        $conversations = collect();

        foreach ($tripIds as $tripId) {
            $trip = Pastrips::find($tripId);
            if (!$trip) continue;

            $lastMessage = ChatMessage::where('trip_id', $tripId)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$lastMessage) continue;

            $unreadCount = ChatMessage::where('trip_id', $tripId)
                ->where('sender_id', '!=', $userId)
                ->whereNull('read_at')
                ->count();

            // Déterminer l'interlocuteur même si l'utilisateur a écrit seul pour l'instant
            if ($trip->user_id === $userId) {
                $participant = $trip->accepted_by ? User::find($trip->accepted_by) : null;
            } else {
                $participant = User::find($trip->user_id);
            }

            if (!$participant) continue;

            $conversations->push((object)[
                'trip_id'      => $tripId,
                'lastMessage'  => $lastMessage,
                'trip'         => $trip,
                'participant'  => $participant,
                'unread_count' => $unreadCount,
            ]);
        }

        $conversations = $conversations
            ->sortByDesc(fn($c) => $c->lastMessage->created_at)
            ->values();

        $conversationsData = $conversations->map(fn($c) => [
            'trip_id'         => $c->trip_id,
            'name'            => trim(($c->participant->first_name ?? '') . ' ' . ($c->participant->last_name ?? $c->participant->name ?? '')),
            'initial'         => strtoupper(substr($c->participant->first_name ?? $c->participant->name ?? '?', 0, 1)),
            'role'            => $c->participant->role ?? 'passenger',
            'departure_city'  => $c->trip->departure_city ?? '',
            'arrival_city'    => $c->trip->arrival_city ?? '',
            'url_poll'        => route('messages.poll',        $c->trip_id),
            'url_send'        => route('messages.send',        $c->trip_id),
            'url_read'        => route('messages.read',        $c->trip_id),
            'url_typing'      => route('messages.typing',      $c->trip_id),
            'url_typing_stop' => route('messages.typing.stop', $c->trip_id),
        ])->values();

        return view('messages', [
            'conversations'     => $conversations,
            'conversationsData' => $conversationsData,
        ]);
    }

    public function poll($tripId, Request $request)
    {
        $userId = Auth::id();
        $after = $request->get('after', 0);

        // Vérifier que l'utilisateur est bien participant (passager ou conducteur acceptant)
        $trip = Pastrips::where('id', $tripId)
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhere('accepted_by', $userId);
            })
            ->first();

        if (!$trip) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Récupérer les messages
        $messages = ChatMessage::where('trip_id', $tripId)
            ->where('id', '>', $after)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Marquer comme lus les messages des autres utilisateurs
        ChatMessage::where('trip_id', $tripId)
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Vérifier si l'autre utilisateur est en train d'écrire
        $isTyping = $this->checkTypingStatus($tripId, $userId);

        return response()->json([
            'messages' => $messages,
            'is_typing' => $isTyping,
        ]);
    }

    public function send($tripId, Request $request)
    {
        $userId = Auth::id();

        // Vérifier que l'utilisateur est participant ET que la course est acceptée
        $trip = Pastrips::where('id', $tripId)
            ->where('status', 'accepted')
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhere('accepted_by', $userId);
            })
            ->first();

        if (!$trip) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = ChatMessage::create([
            'trip_id' => $tripId,
            'sender_id' => $userId,
            'content' => $request->content,
            'read_at' => null,
        ]);

        $message->load('sender');

        return response()->json([
            'message' => $message,
        ]);
    }

    public function markRead($tripId)
    {
        $userId = Auth::id();

        // Marquer comme lus tous les messages des autres utilisateurs
        ChatMessage::where('trip_id', $tripId)
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function typingStart($tripId)
    {
        $userId = Auth::id();

        // Stocker l'état de saisie dans le cache (5 secondes)
        Cache::put("typing:{$tripId}:" . $userId, true, now()->addSeconds(5));

        return response()->json(['success' => true]);
    }

    public function typingStop($tripId)
    {
        $userId = Auth::id();
        Cache::forget("typing:{$tripId}:" . $userId);
        return response()->json(['success' => true]);
    }

    private function checkTypingStatus($tripId, $currentUserId)
    {
        // Récupérer l'autre participant dans la conversation
        $otherUserId = ChatMessage::where('trip_id', $tripId)
            ->where('sender_id', '!=', $currentUserId)
            ->distinct()
            ->value('sender_id');

        if (!$otherUserId) return false;

        return Cache::has("typing:{$tripId}:{$otherUserId}");
    }
}
