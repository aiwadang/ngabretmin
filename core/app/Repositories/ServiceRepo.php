<?php

namespace App\Repositories;

use App\Models\Service;

class ServiceRepo {
    public function getServices() {
        return Service::active()->get();
    }
}
