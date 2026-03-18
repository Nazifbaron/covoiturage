<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PassengerController;
use Illuminate\Support\Facades\Route;


Route::view('/','welcome');
Route::view('/contact','contact');
Route::view('/about','about');
Route::view('/result','resultat');
Route::view('/detail','details');
Route::view('/marche','marche');
Route::view('/search','search');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');

Route::middleware('auth')->group(function () {

     Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

       Route::controller(AuthenticatedSessionController::class)->group(function () {
        Route::get('/choose-role', 'showRoleSelection')->name('user.role.show');
        Route::post('/choose-role', 'storeRole')->name('user.role.store');
    });

    Route::controller(DriverController::class)->group(function () {
        Route::get('/driver/create-tips', 'showCreateTips')->name('driver.create-tips');
        Route::get('/driver/requests', 'requests')->name('driver.requests');
        Route::post('/driver/requests/{pastrip}/accept', 'acceptRequest')->name('driver.accept');
        Route::get('/driver/chat/{pastrip}','chat')->name('driver.chat');
        Route::post('/driver/vehicle',   'save')   ->name('vehicle.save');
        Route::delete('/driver/vehicle', 'destroy')->name('vehicle.destroy');

         Route::post('/driver/trips', 'storeTrip')  ->name('driver.trips.store');
        Route::get('/driver/mytrips', 'myTrips')    ->name('driver.my-trips');
         Route::patch('/driver/trips/{trip}/cancel', 'cancelTrip')->name('driver.trips.cancel');
    });

    Route::controller(PassengerController::class)->group(function () {
        Route::get('/passenger/create-request', 'showCreateRequest')->name('passenger.showtrips');
        Route::post('/passenger/requests', 'storetrips')->name('passenger.storetrips');
        Route::get('/passenger/my-requests', 'showMyRequests')->name('passenger.my-requests');
        Route::get('/passenger/chat/{pastrip}', 'chat')->name('passenger.chat');
        Route::get('/passenger/trips',        'availableTrips')->name('passenger.trips');
        Route::get('/passenger/trips/search', 'searchTrips')   ->name('passenger.trips.search');
    });

    // Route lié aux chat entre conducteur et passager

      Route::controller(DriverController::class)->prefix('chat/{pastrip}')->group(function () {
        Route::get('/messages',   'pollMessages')->name('chat.poll');
         Route::post('/read',      'markRead')    ->name('chat.read');
        Route::post('/typing',    'typingStart') ->name('chat.typing');
         Route::post('/typing/stop','typingStop') ->name('chat.typing.stop');


     });


});

require __DIR__.'/auth.php';
