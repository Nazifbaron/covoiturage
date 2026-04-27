<?php

namespace App\Events;

use App\Models\Vehicle;
use Illuminate\Foundation\Events\Dispatchable;

class VehicleDocumentExpired
{
    use Dispatchable;

    public function __construct(
        public Vehicle $vehicle,
        public array $expiredDocs
    ) {}
}
