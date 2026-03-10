<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PassengerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
    });

    Route::controller(PassengerController::class)->group(function () {
        Route::get('/passenger/create-request', 'showCreateRequest')->name('passenger.showtrips');
        Route::post('/passenger/requests', 'storetrips')->name('passenger.storetrips');
        Route::get('/passenger/my-requests', 'showMyRequests')->name('passenger.my-requests');
    });

});

require __DIR__.'/auth.php';
