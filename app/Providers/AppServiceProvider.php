<?php

namespace App\Providers;

use App\Events\VehicleDocumentExpired;
use App\Events\VehicleDocumentExpiringSoon;
use App\Listeners\BlockDriverForExpiredDocuments;
use App\Listeners\SendDocumentExpirationEmail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Avertissement avant expiration → email au conducteur
        Event::listen(
            VehicleDocumentExpiringSoon::class,
            SendDocumentExpirationEmail::class,
        );

        // Document expiré → bloquer le conducteur + email
        Event::listen(
            VehicleDocumentExpired::class,
            BlockDriverForExpiredDocuments::class,
        );
    }
}
