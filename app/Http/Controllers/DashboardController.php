<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\DriverTrips;
use App\Models\Pastrips;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'driver') {
            return $this->driverDashboard($user);
        }

        return $this->passengerDashboard($user);
    }

    private function driverDashboard($user)
    {
        $driverId = $user->id;

        // ── Stats ─────────────────────────────────────────────────
        $allTrips = DriverTrips::where('driver_id', $driverId)
            ->where('status', '!=', 'cancelled')
            ->get();

        $earning = fn(DriverTrips $t) => $t->price_per_seat * ($t->seats_total - $t->seats_available);

        $thisMonthEarnings = $allTrips
            ->filter(fn($t) => $t->departure_date->month === now()->month
                             && $t->departure_date->year  === now()->year)
            ->sum($earning);

        $tripsCount = DriverTrips::where('driver_id', $driverId)->count();

        // IDs des trajets du conducteur connecté (réutilisé plusieurs fois)
        $driverTripIds = DriverTrips::where('driver_id', $driverId)->pluck('id');

        $pendingRequestsCount = Pastrips::where('status', 'pending')
            ->whereIn('driver_trip_id', $driverTripIds)
            ->count();

        // ── Trajets récents ────────────────────────────────────────
        $recentTrips = DriverTrips::where('driver_id', $driverId)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // ── Demandes passagers en attente (sur les trajets du conducteur) ──
        Pastrips::whereIn('driver_trip_id', $driverTripIds)
            ->where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        $pendingPastrips = Pastrips::with('user')
            ->where('status', 'pending')
            ->whereIn('driver_trip_id', $driverTripIds)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'thisMonthEarnings',
            'tripsCount',
            'pendingRequestsCount',
            'recentTrips',
            'pendingPastrips',
        ));
    }

    private function passengerDashboard($user)
    {
        $userId = $user->id;

        // Expirer les demandes périmées
        Pastrips::where('user_id', $userId)
            ->where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        // ── Stats ─────────────────────────────────────────────────
        $activeCount    = Pastrips::where('user_id', $userId)->whereIn('status', ['pending', 'accepted'])->count();
        $completedCount = Pastrips::where('user_id', $userId)->where('status', 'accepted')->count();
        $pendingCount   = Pastrips::where('user_id', $userId)->where('status', 'pending')->count();

        // ── Réservations récentes (liées à un trajet conducteur) ───
        $recentReservations = Pastrips::with(['driverTrip.driver'])
            ->where('user_id', $userId)
            ->whereNotNull('driver_trip_id')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // ── Demande en cours (la plus récente sans conducteur lié) ─
        $currentRequest = Pastrips::where('user_id', $userId)
            ->where('status', 'pending')
            ->whereNull('driver_trip_id')
            ->latest()
            ->first();

        // ── Historique ─────────────────────────────────────────────
        $history = Pastrips::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('dashboard', compact(
            'activeCount',
            'completedCount',
            'pendingCount',
            'recentReservations',
            'currentRequest',
            'history',
        ));
    }
}
