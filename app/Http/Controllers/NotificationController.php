<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /** Page complète ou JSON (dropdown) selon le type de requête */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Requête AJAX du dropdown → JSON
        if ($request->expectsJson()) {
            $notifications = $user->notifications()
                ->latest()
                ->take(20)
                ->get()
                ->map(fn($n) => [
                    'id'         => $n->id,
                    'data'       => $n->data,
                    'read'       => !is_null($n->read_at),
                    'created_at' => $n->created_at->diffForHumans(),
                ]);

            return response()->json([
                'notifications' => $notifications,
                'unread_count'  => $user->unreadNotifications()->count(),
            ]);
        }

        // Requête navigateur → vue complète paginée
        $filter = $request->query('filter', 'all');

        $query = $user->notifications()->latest();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->paginate(15)->withQueryString();
        $unreadCount   = $user->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount', 'filter'));
    }

    /** Marque une notification comme lue et redirige vers son URL */
    public function markRead(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $url = $notification->data['url'] ?? route('dashboard');

        return redirect($url);
    }

    /** Marque toutes les notifications comme lues */
    public function markAllRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('notifications.index', ['filter' => $request->query('filter', 'all')])
                         ->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /** Supprime une notification */
    public function destroy(Request $request, string $id)
    {
        Auth::user()->notifications()->findOrFail($id)->delete();

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->back()->with('success', 'Notification supprimée.');
    }

    /** Supprime toutes les notifications */
    public function destroyAll(Request $request)
    {
        Auth::user()->notifications()->delete();

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('notifications.index')
                         ->with('success', 'Toutes les notifications ont été supprimées.');
    }
}
