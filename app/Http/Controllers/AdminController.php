<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\DriverTrips;
use App\Models\Pastrips;
use App\Models\Vehicle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\VehicleApproved;
use App\Mail\VehicleRejected;
use App\Notifications\VehicleStatusChanged;

class AdminController extends Controller
{
    // ─── Helpers ────────────────────────────────────────────────────────────────

    private function isSuperAdmin(): bool
    {
        return (bool) auth()->user()->is_super_admin;
    }

    private function hasAccess(string $permission): bool
    {
        return $this->isSuperAdmin() || auth()->user()->can($permission);
    }

    // ─── Dashboard ──────────────────────────────────────────────────────────────

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

        $newUsersWeek = User::where('role', '!=', 'admin')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $newTripsWeek = DriverTrips::where('created_at', '>=', now()->subDays(7))->count();

        return view('admin.dashboard', compact('stats', 'newUsersWeek', 'newTripsWeek'));
    }

    // ─── Utilisateurs ───────────────────────────────────────────────────────────

    public function users(Request $request)
    {
        abort_unless($this->hasAccess('manage_users'), 403);

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

    public function blockUser(User $user)
    {
        abort_unless($this->hasAccess('manage_users'), 403);
        abort_if($user->role === 'admin', 403);

        $wasBlocked = (bool) $user->is_blocked;
        $user->update(['is_blocked' => !$wasBlocked]);
        $action = !$wasBlocked ? 'bloqué' : 'débloqué';

        return redirect()->back()->with('success', "Utilisateur {$action} avec succès.");
    }

    public function deleteUser(User $user)
    {
        abort_unless($this->hasAccess('manage_users'), 403);
        abort_if($user->role === 'admin', 403);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé.');
    }

    // ─── Véhicules ──────────────────────────────────────────────────────────────

    public function vehicles(Request $request)
    {
        abort_unless($this->hasAccess('manage_vehicles'), 403);

        $query = Vehicle::with('driver')->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'LIKE', "%{$search}%")
                  ->orWhere('model', 'LIKE', "%{$search}%")
                  ->orWhere('plate', 'LIKE', "%{$search}%")
                  ->orWhereHas('driver', fn($d) => $d
                      ->where('first_name', 'LIKE', "%{$search}%")
                      ->orWhere('last_name',  'LIKE', "%{$search}%")
                  );
            });
        }

        $vehicles = $query->paginate(15)->withQueryString();

        return view('admin.vehicles', compact('vehicles'));
    }

    public function approveVehicle(Vehicle $vehicle)
    {
        abort_unless($this->hasAccess('manage_vehicles'), 403);

        $vehicle->update([
            'status'           => 'approved',
            'approved_at'      => now(),
            'rejection_reason' => null,
        ]);

        try {
            Mail::to($vehicle->driver->email)
                ->send(new VehicleApproved($vehicle->load('driver')));
        } catch (\Exception $e) {
            \Log::error('Échec mail approbation véhicule : ' . $e->getMessage());
        }

        try {
            $vehicle->driver->notify(new VehicleStatusChanged($vehicle, 'approved'));
        } catch (\Exception $e) {
            \Log::warning('Notification VehicleStatusChanged échouée : ' . $e->getMessage());
        }

        return redirect()->back()->with('success', "Véhicule de {$vehicle->driver->first_name} approuvé.");
    }

    public function rejectVehicle(Request $request, Vehicle $vehicle)
    {
        abort_unless($this->hasAccess('manage_vehicles'), 403);

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ], [
            'rejection_reason.required' => 'Veuillez indiquer le motif du rejet.',
        ]);

        $vehicle->update([
            'status'           => 'rejected',
            'approved_at'      => null,
            'rejection_reason' => $request->rejection_reason,
        ]);

        try {
            Mail::to($vehicle->driver->email)
                ->send(new VehicleRejected($vehicle->load('driver')));
        } catch (\Exception $e) {
            \Log::error('Échec mail rejet véhicule : ' . $e->getMessage());
        }

        try {
            $vehicle->driver->notify(new VehicleStatusChanged($vehicle->load('driver'), 'rejected'));
        } catch (\Exception $e) {
            \Log::warning('Notification VehicleStatusChanged échouée : ' . $e->getMessage());
        }

        return redirect()->back()->with('success', "Véhicule rejeté.");
    }

    public function deleteVehicle(Vehicle $vehicle)
    {
        abort_unless($this->hasAccess('manage_vehicles'), 403);
        $vehicle->delete();
        return redirect()->route('admin.vehicles')->with('success', 'Véhicule supprimé.');
    }

    // ─── Trajets ────────────────────────────────────────────────────────────────

    public function trips(Request $request)
    {
        abort_unless($this->hasAccess('manage_trips'), 403);

        $query = DriverTrips::with(['driver', 'driver.vehicle'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('departure_city', 'LIKE', "%{$search}%")
                  ->orWhere('arrival_city',  'LIKE', "%{$search}%");
            });
        }

        $trips = $query->paginate(15)->withQueryString();

        return view('admin.trips', compact('trips'));
    }

    // ─── Réservations ───────────────────────────────────────────────────────────

    public function reservations(Request $request)
    {
        abort_unless($this->hasAccess('manage_reservations'), 403);

        $query = Pastrips::with(['user', 'driverTrip'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->paginate(15)->withQueryString();

        return view('admin.reservations', compact('reservations'));
    }

    // ─── Gestion des admins (super admin uniquement) ─────────────────────────────

    public function adminsList()
    {
        abort_unless($this->isSuperAdmin(), 403);

        $admins = User::where('role', 'admin')
            ->where('is_super_admin', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $allPermissions = ['manage_users', 'manage_trips', 'manage_vehicles', 'manage_reservations'];

        return view('admin.admins', compact('admins', 'allPermissions'));
    }

    public function addAdmin()
    {
        abort_unless($this->isSuperAdmin(), 403);
        return view('admin.add-admin');
    }

    public function storeAdmin(Request $request)
    {
        abort_unless($this->isSuperAdmin(), 403);

        $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name'  => ['required', 'string', 'max:50'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'password'   => ['required', 'confirmed', Password::min(8)],
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required'  => 'Le nom est obligatoire.',
            'email.required'      => 'L\'adresse email est obligatoire.',
            'email.unique'        => 'Cette adresse email est déjà utilisée.',
            'password.required'   => 'Le mot de passe est obligatoire.',
            'password.confirmed'  => 'Les mots de passe ne correspondent pas.',
        ]);

        User::create([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'role'           => 'admin',
            'is_super_admin' => false,
            'password'       => Hash::make($request->password),
        ]);

        return redirect()->route('admin.admins.list')
            ->with('success', "Administrateur {$request->first_name} {$request->last_name} créé avec succès.");
    }

    public function editAdminPermissions(User $admin)
    {
        abort_unless($this->isSuperAdmin(), 403);
        abort_if($admin->is_super_admin, 403);

        $allPermissions = ['manage_users', 'manage_trips', 'manage_vehicles', 'manage_reservations'];

        return view('admin.admin-permissions', compact('admin', 'allPermissions'));
    }

    public function updateAdminPermissions(Request $request, User $admin)
    {
        abort_unless($this->isSuperAdmin(), 403);
        abort_if($admin->is_super_admin, 403);

        $allowed = ['manage_users', 'manage_trips', 'manage_vehicles', 'manage_reservations'];
        $selected = array_intersect($request->input('permissions', []), $allowed);

        $admin->syncPermissions($selected);

        return redirect()->route('admin.admins.list')
            ->with('success', "Accès de {$admin->first_name} {$admin->last_name} mis à jour.");
    }

    public function deleteAdmin(User $admin)
    {
        abort_unless($this->isSuperAdmin(), 403);
        abort_if($admin->is_super_admin, 403);

        $admin->delete();

        return redirect()->route('admin.admins.list')
            ->with('success', 'Administrateur supprimé.');
    }
}
