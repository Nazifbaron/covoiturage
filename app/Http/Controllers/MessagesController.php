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

        // Tous les trip_id où l'utilisateur a participé
        $tripIds = ChatMessage::where('sender_id', $userId)
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

            $otherUserId = ChatMessage::where('trip_id', $tripId)
                ->where('sender_id', '!=', $userId)
                ->value('sender_id');

            $participant = $otherUserId ? User::find($otherUserId) : null;
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

        // Vérifier que l'utilisateur a accès à ce trip
        $hasAccess = ChatMessage::where('trip_id', $tripId)
            ->where('sender_id', $userId)
            ->exists();

        if (!$hasAccess) {
            // Vérifier si l'utilisateur est destinataire d'un message
            $hasAccess = ChatMessage::where('trip_id', $tripId)
                ->where('sender_id', '!=', $userId)
                ->exists();
        }

        if (!$hasAccess) {
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

        // Vérifier que l'utilisateur peut envoyer un message sur ce trip
        // Soit il a déjà envoyé des messages, soit il est lié au trip
        $canSend = ChatMessage::where('trip_id', $tripId)
            ->where('sender_id', $userId)
            ->exists();

        if (!$canSend) {
            // Vérifier si le trip existe
            $trip = Pastrips::find($tripId);
            if (!$trip) {
                return response()->json(['error' => 'Trip not found'], 404);
            }
            $canSend = true; // Premier message, autorisé
        }

        if (!$canSend) {
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
        Cache::put("typing_{$tripId}_" . $userId, true, now()->addSeconds(5));

        return response()->json(['success' => true]);
    }

    public function typingStop($tripId)
    {
        $userId = Auth::id();
        Cache::forget("typing_{$tripId}_" . $userId);
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

        return Cache::has("typing_{$tripId}_{$otherUserId}");
    }
}
