<?php

namespace App\Repositories;

use App\Models\Zone;

class ZoneRepo {
    public function getZones() {
        return Zone::active()->get();
    }
}
