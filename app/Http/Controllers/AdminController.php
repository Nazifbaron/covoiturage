<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\DriverTrips;
use App\Models\Pastrips;
use App\Models\Vehicle;

use Illuminate\Http\Request;

class AdminController extends Controller
{
     public function dashboard()
    {
        $stats = [
            'users_total'      => User::where('role', '!=', 'admin')->count(),
            'drivers'          => User::where('role', 'driver')->count(),
            'passengers'       => User::where('role', 'passenger')->count(),
            'trips_total'      => DriverTrips::count(),
            'trips_scheduled'  => DriverTrips::where('status', 'scheduled')->count(),
            'trips_completed'  => DriverTrips::where('status', 'completed')->count(),
            'trips_cancelled'  => DriverTrips::where('status', 'cancelled')->count(),
            'reservations'     => Pastrips::count(),
            'res_pending'      => Pastrips::where('status', 'pending')->count(),
            'res_accepted'     => Pastrips::where('status', 'accepted')->count(),
            'vehicles'         => Vehicle::count(),
        ];

        // Inscriptions des 7 derniers jours
        $newUsersWeek = User::where('role', '!=', 'admin')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        // Trajets des 7 derniers jours
        $newTripsWeek = DriverTrips::where('created_at', '>=', now()->subDays(7))->count();

        return view('admin.dashboard', compact('stats', 'newUsersWeek', 'newTripsWeek'));
    }

    public function users(Request $request)
    {
        $query = User::where('role', '!=', 'admin')
            ->withCount(['driverTrips', 'passengerTrips'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name',  'LIKE', "%{$search}%")
                  ->orWhere('email',      'LIKE', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('is_blocked', $request->status === 'blocked');
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users', compact('users'));
    }

    //  public function blockUser(User $user)
    // {
    //     abort_if($user->role === 'admin', 403);
    //     $user->update(['is_blocked' => !$user->is_blocked]);
    //     $action = $user->is_blocked ? 'bloqué' : 'débloqué';

    //     return redirect()->back()->with('success', "Utilisateur {$action} avec succès.");
    // }


    public function blockUser(User $user)
    {
        abort_if($user->role === 'admin', 403);

        // Lire la valeur AVANT la mise à jour
        $wasBlocked = (bool) $user->is_blocked;

        $user->update(['is_blocked' => !$wasBlocked]);

        $action = !$wasBlocked ? 'bloqué' : 'débloqué';

        return redirect()->back()->with('success', "Utilisateur {$action} avec succès.");
    }

    public function deleteUser(User $user)
    {
        abort_if($user->role === 'admin', 403);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé.');
    }
}
