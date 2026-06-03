<?php

namespace App\Services;

use App\Repositories\ZoneRepo;

class ZoneService {
    public function __construct(
        protected ZoneRepo $zoneRepo
    ) {
    }

    public function checkPickupAndDestinationZone($request): array {
        $zones           = $this->zoneRepo->getZones();
        $pickupAddress   = ['lat' => $request->pickup_latitude, 'long' => $request->pickup_longitude];
        $pickupZone      = null;
        $destinationZone = null;

        foreach ($zones as $zone) {
            $pickupZone = insideZone($pickupAddress, $zone);
            if ($pickupZone) {
                $pickupZone = $zone;
                break;
            }
        }

        if (!$pickupZone) {
            return [
                'status'  => 'error',
                'message' => 'The pickup location is not inside any of our zones'
            ];
        }

        $destinationAddress = ['lat' => $request->destination_latitude, 'long' => $request->destination_longitude];

        foreach ($zones as $zone) {
            $destinationZone = insideZone($destinationAddress, $zone);

            if ($destinationZone) {
                $destinationZone = $zone;
                break;
            }
        }

        if (!$destinationZone) {
            return [
                'status'  => 'error',
                'message' => 'The destination location is not inside any of our zones'
            ];
        }
        return [
            'pickup_zone'      => $pickupZone,
            'destination_zone' => $destinationZone,
            'status'           => 'success'
        ];
    }
}
