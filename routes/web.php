<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PassengerController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\Checkblocked;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\NotificationController;


// Route::view('/','welcome');
// Route::view('/contact','contact');
// Route::view('/about','about');
// Route::view('/result','resultat');
// Route::view('/detail','details');

Route::view('/','welcome');
Route::view('/contact','contact');
Route::view('/about','about');
Route::view('/result','resultat');
Route::view('/detail','details');
Route::view('/marche','marche');
Route::view('/search','search');

// Route accessible même si bloqué (hors middleware auth)
Route::get('/blocked', function () {
    // Si pas connecté → login
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    // Si pas bloqué → dashboard
    if (!auth()->user()->is_blocked) {
        return redirect()->route('dashboard');
    }
    return view('blocked');
})->middleware('auth')->name('blocked');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'vehicle_approval'])->name('dashboard');

Route::middleware(['auth', Checkblocked::class])->group(function () {

     Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::post('/profile/avatar', 'updateAvatar')->name('profile.avatar.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

       Route::controller(AuthenticatedSessionController::class)->group(function () {
        Route::get('/choose-role', 'showRoleSelection')->name('user.role.show');
        Route::post('/choose-role', 'storeRole')->name('user.role.store');
    });

    // Routes véhicule accessibles sans approbation (setup/pending) — conducteurs uniquement
    Route::controller(DriverController::class)->middleware('check_role:driver')->group(function () {
        Route::get('/driver/vehicle/pending', 'pending')       ->name('driver.vehicle.pending');
        Route::get('/driver/vehicle/setup',   'showVehicleSetup')->name('driver.vehicle.setup');
        Route::post('/driver/vehicle/store',  'storeVehicle')  ->name('driver.vehicle.store');
    });

    // Routes driver protégées — véhicule approuvé obligatoire
    Route::controller(DriverController::class)
        ->middleware(['check_role:driver', 'vehicle_approval'])
        ->group(function () {
            Route::get('/driver/create-tips', 'showCreateTips')->name('driver.create-tips');
            Route::get('/driver/requests', 'requests')->name('driver.requests');
            Route::post('/driver/requests/{pastrip}/accept', 'acceptRequest')->name('driver.accept');
            Route::get('/driver/chat/{pastrip}', 'chat')->name('driver.chat');
            Route::delete('/driver/vehicle/{vehicle}', 'destroy')->name('vehicle.destroy');
            Route::get('/driver/trips/create', 'showCreateTips')->name('driver.trips.create');
            Route::post('/driver/trips', 'storeTrip')  ->name('driver.trips.store');
            Route::get('/driver/mytrips', 'myTrips')    ->name('driver.my-trips');
            Route::patch('/driver/trips/{trip}/cancel', 'cancelTrip')->name('driver.trips.cancel');
            Route::get('/driver/earnings', 'earnings')->name('driver.earnings');
        });

    Route::controller(PassengerController::class)->middleware('check_role:passenger')->group(function () {
        Route::get('/passenger/create-request', 'showCreateRequest')->name('passenger.showtrips');
        Route::post('/passenger/requests', 'storetrips')->name('passenger.storetrips');
        Route::get('/passenger/my-requests', 'showMyRequests')->name('passenger.my-requests');
        Route::get('/passenger/chat/{pastrip}', 'chat')->name('passenger.chat');
        Route::patch('/passenger/requests/{pastrip}/cancel', 'cancelRequest')->name('passenger.requests.cancel');
        Route::get('/passenger/trips',        'availableTrips')->name('passenger.trips');
        Route::get('/passenger/trips/search', 'searchTrips')   ->name('passenger.trips.search');
    });

    // Route lié aux chat entre conducteur et passager

      Route::controller(DriverController::class)->prefix('chat/{pastrip}')->group(function () {
        Route::get('/messages',   'pollMessages')->name('chat.poll');
        Route::post('/send',       'sendMessage')->name('chat.send');
         Route::post('/read',      'markRead')    ->name('chat.read');
        Route::post('/typing',    'typingStart') ->name('chat.typing');
         Route::post('/typing/stop','typingStop') ->name('chat.typing.stop');
     });

    Route::controller(NotificationController::class)->group(function () {
        Route::get('/notifications',          'index')      ->name('notifications.index');
        Route::get('/notifications/{id}/read','markRead')   ->name('notifications.read');
        Route::post('/notifications/read-all','markAllRead')->name('notifications.read-all');
    });

     Route::controller(MessagesController::class)->group(function () {
        Route::get('/messages', 'index')->name('messages.index');
        Route::get('/messages/{tripId}/poll', 'poll')->name('messages.poll');
        Route::post('/messages/{tripId}/send', 'send')->name('messages.send');
        Route::post('/messages/{tripId}/read', 'markRead')->name('messages.read');
        Route::post('/messages/{tripId}/typing', 'typingStart')->name('messages.typing');
        Route::post('/messages/{tripId}/typing/stop', 'typingStop')->name('messages.typing.stop');
        });




});


Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')
    ->group(function () {

    Route::controller(AdminController::class)->group(function () {
        // Dashboard
        Route::get('/', 'dashboard')->name('dashboard');

        // Utilisateurs
        Route::get('/users', 'users')->name('users');
        Route::patch('/users/{user}/block', 'blockUser')  ->name('users.block');
        Route::delete('/users/{user}','deleteUser') ->name('users.delete');

        // Trajets conducteurs
        Route::get('/trips',  'trips') ->name('trips');
        Route::patch('/trips/{trip}/cancel','cancelTrip')->name('trips.cancel');

        // Réservations passagers
        Route::get('/reservations','reservations')->name('reservations');

        // Administrateurs (super admin uniquement)
        Route::get('/admins',                          'adminsList')            ->name('admins.list');
        Route::get('/admins/create',                   'addAdmin')              ->name('admins.create');
        Route::post('/admins',                         'storeAdmin')            ->name('admins.store');
        Route::get('/admins/{admin}/permissions',      'editAdminPermissions')  ->name('admins.permissions.edit');
        Route::patch('/admins/{admin}/permissions',    'updateAdminPermissions')->name('admins.permissions.update');
        Route::delete('/admins/{admin}',               'deleteAdmin')           ->name('admins.delete');

        // Véhicules
        Route::get('/vehicles','vehicles')->name('vehicles');
        Route::patch('/vehicles/{vehicle}/approve', 'approveVehicle')->name('vehicles.approve');
        Route::patch('/vehicles/{vehicle}/reject',  'rejectVehicle') ->name('vehicles.reject');
        Route::delete('/vehicles/{vehicle}', 'deleteVehicle')->name('vehicles.delete');
        });
    });

require __DIR__.'/auth.php';
