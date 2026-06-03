<?php

namespace App\Services\Rider;

use App\Constants\Status;
use App\Models\User;
use App\Repositories\RideRepo;
use App\Events\Ride as EventsRide;
use Barryvdh\DomPDF\Facade\Pdf;

class RideService
{
    protected $rideRepo;

    public function __construct(RideRepo $rideRepo)
    {
        $this->rideRepo = $rideRepo;
    }

    // Controller access start

    public function getFareAndRouteData(float $pickupLat, float $pickupLng, float $destLat, float $destLng): array
    {
        $zoneData = $this->detectZones($pickupLat, $pickupLng, $destLat, $destLng);


        if ($zoneData['status'] == 'error') {
            return $zoneData;
        }

        $mapData = $this->getGoogleMapData($pickupLat, $pickupLng, $destLat, $destLng);



        if ($mapData['status'] == 'error') {
            return serviceError('api_error', $mapData['message'] ?? trans('Unknown map error'));
        }

        $fareData = $this->calculateFare(
            $zoneData['pickup_zone'],
            $zoneData['destination_zone'],
            $mapData['distance']
        );

        return serviceSuccess('ride_data', trans('Fare calculated successfully'), [
            'pickup_zone'      => $zoneData['pickup_zone'],
            'destination_zone' => $zoneData['destination_zone'],
            'distance'         => $mapData['distance'],
            'duration'         => $mapData['duration'],
            'ride_type'        => $fareData['ride_type'],
            'services'         => $fareData['services'],
        ]);
    }

    public function createRide(array $requestData, $user): array
    {
        $activeRideExist = $this->rideRepo->hasActiveRide($user->id);

        if ($activeRideExist) {
            return serviceError('active_ride_exist', trans('You can create a ride, after finish an ongoing ride.'));
        }

        $service = $this->rideRepo->findActiveService($requestData['service_id']);

        if (!$service) {
            return serviceError('not_found', trans('This service is currently unavailable'));
        }

        $route = $this->resolveRouteData($requestData);

        if ($route['status'] === 'error') {
            return $route;
        }

        $pickupZone      = $route['pickup_zone'];
        $destinationZone = $route['destination_zone'];
        $distance        = $route['distance'];
        $duration        = $route['duration'];

        $fareData = $this->calculateFareForRideCreation($service, $pickupZone, $destinationZone, $distance);


        if (getAmount($requestData['offer_amount']) < getAmount(gs('min_fare'))) {
            return serviceError(
                'limit_error',
                trans('Minimum offer amount is :minAmount for any ride', [
                    'minAmount' => showAmount(gs('min_fare')),
                ])
            );
        }

        if (getAmount($requestData['offer_amount']) < getAmount($fareData['min_amount'])) {
            return serviceError(
                'limit_error',
                trans('Minimum offer amount is :minAmount for :service service', [
                    'minAmount' => showAmount($fareData['min_amount']),
                    'service'   => $service->name
                ])
            );
        }

        if (getAmount($requestData['offer_amount']) > getAmount($fareData['max_amount'])) {
            return serviceError(
                'limit_error',
                trans('Maximum offer amount is :maxAmount for :service service', [
                    'maxAmount' => showAmount($fareData['max_amount']),
                    'service'   => $service->name
                ])
            );
        }

        $penaltyAmount = $user->penalty_amount ?? 0;
        $finalAmount   = $requestData['offer_amount'] + $penaltyAmount;

        $rideData = [
            'uid'                   => getTrx(10),
            'user_id'               => $user->id,
            'service_id'            => $requestData['service_id'],
            'pickup_location'       => $requestData['pickup_location'],
            'pickup_latitude'       => $requestData['pickup_latitude'],
            'pickup_longitude'      => $requestData['pickup_longitude'],
            'destination'           => $requestData['destination_location'],
            'destination_latitude'  => $requestData['destination_latitude'],
            'destination_longitude' => $requestData['destination_longitude'],
            'ride_type'             => $fareData['ride_type'],
            'note'                  => $requestData['note'] ?? null,
            'number_of_passenger'   => $requestData['number_of_passenger'],
            'distance'              => $distance,
            'duration'              => $duration,
            'pickup_zone_id'        => $pickupZone->id,
            'destination_zone_id'   => $destinationZone->id,
            'recommend_amount'      => $fareData['recommend_amount'],
            'min_amount'            => $fareData['min_amount'],
            'max_amount'            => $fareData['max_amount'],
            'amount'                => $finalAmount,
            'payment_type'          => $requestData['payment_type'],
            'commission_percentage' => $fareData['commission_percentage'],
            'gateway_currency_id'   => $requestData['payment_type'] == Status::PAYMENT_TYPE_GATEWAY ? $requestData['gateway_currency_id'] : 0,
        ];

        $ride = $this->rideRepo->createRide($rideData);

        $ride->load('user', 'service', 'driver', 'driver.vehicle', 'driver.vehicle.model', 'driver.vehicle.color', 'driver.vehicle.year');

        if ($penaltyAmount > 0) {
            $this->rideRepo->minimizeUserPenalty($user);
        }

        $this->rideRepo->createRideQueue($ride->id);

        return serviceSuccess('ride_create_success', trans('Ride initiated successfully. Finding the nearest driver in your area.'), [
            'ride'    => $ride
        ]);
    }

    public function getRideDetailsForUser(int $rideId, int $userId): array
    {
        $ride = $this->rideRepo->findUserRideWithRelations($rideId, $userId);

        if (!$ride) {
            return serviceError('not_found', trans('The requested ride could not be found.'));
        }

        $driverTotalRide = 0;

        if ($ride->driver_id) {
            $driverTotalRide = $this->rideRepo->countCompletedDriverRides($ride->driver_id, $ride->id);
        }

        return serviceSuccess('ride_details', trans('Ride details fetched successfully'), [
            'data' => [
                'ride'               => $ride,
                'service_image_path' => getFilePath('service'),
                'brand_image_path'   => getFilePath('brand'),
                'user_image_path'    => getFilePath('user'),
                'driver_image_path'  => getFilePath('driver'),
                'driver_total_ride'  => $driverTotalRide,
            ]
        ]);
    }

    public function cancelRide(int $rideId, User $user, string $cancelReason): array
    {
        $ride = $this->rideRepo->findCancelableRideByUser($rideId, $user->id);

        if (!$ride) {
            return serviceError('not_found', trans('The requested ride could not be found.'));
        }

        $cancelCount    = $this->rideRepo->countUserCancellations($user->id);
        $penaltyApplied = $cancelCount >= gs('user_cancellation_limit');

        if ($penaltyApplied) {
            $this->rideRepo->applyUserPenalty($user, gs('user_cancellation_penalty'));
        }

        $this->rideRepo->cancelRide($ride, $cancelReason, Status::USER);

        if ($ride->driver_id) {
            event(new EventsRide("rider-driver-$ride->driver_id", "CANCEL_RIDE", ['ride' => $ride]));

            notify($ride->driver, 'CANCEL_RIDE', [
                'ride_id'         => $ride->uid,
                'reason'          => $ride->cancel_reason,
                'amount'          => showAmount($ride->amount, currencyFormat: false),
                'service'         => $ride->service->name,
                'pickup_location' => $ride->pickup_location,
                'destination'     => $ride->destination,
                'duration'        => $ride->duration,
                'distance'        => $ride->distance,
                'pickup_time'     => showDateTime(now()),
            ]);
        }

        $message = $penaltyApplied
            ? trans('You’ve been charged :amount for ride cancellation', ['amount' => showAmount(gs('user_cancellation_penalty'))])
            : trans('Ride canceled successfully');

        return serviceSuccess('canceled_ride', $message);
    }

    public function acceptBid(int $bidId, User $user): array
    {
        $bid = $this->rideRepo->findPendingBidForUser($bidId, $user->id);

        if (!$bid) {
            return serviceError('not_found', trans('Invalid bid'));
        }

        $hasActiveRide = $this->rideRepo->userHasRideWithStatus($user->id, [Status::RIDE_ACTIVE]);

        if ($hasActiveRide) {
            return serviceError('active_ride', trans('You have an active ride'));
        }

        $hasRunningRide = $this->rideRepo->userHasRideWithStatus($user->id, [Status::RIDE_RUNNING]);

        if ($hasRunningRide) {
            return serviceError('running_ride', trans('You have a running ride'));
        }

        $this->rideRepo->acceptBid($bid);

        $this->rideRepo->rejectOtherBids($bid->id, $bid->ride_id);

        $ride = $this->rideRepo->activateRideFromBid($bid);

        $driverRideCount = $this->rideRepo->countCompletedDriverRides($ride->driver_id, $ride->id);

        event(new EventsRide("rider-driver-$ride->driver_id", 'BID_ACCEPT', [
            'ride'              => $ride,
            'driver_total_ride' => $driverRideCount,
        ]));

        notify($ride->driver, 'ACCEPT_RIDE', [
            'ride_id'         => $ride->uid,
            'amount'          => showAmount($ride->amount),
            'rider'           => $ride->user->username,
            'service'         => $ride->service->name,
            'pickup_location' => $ride->pickup_location,
            'destination'     => $ride->destination,
            'duration'        => $ride->duration,
            'distance'        => $ride->distance,
            'pickup_time'     => showDateTime(now()),
        ]);

        return serviceSuccess('accepted', trans('Bid accepted successfully'), [
            'ride' => $ride
        ]);
    }

    public function rejectBid(int $bidId, User $user): array
    {
        $bid = $this->rideRepo->findPendingBidWithRelations($bidId);

        if (!$bid) {
            return serviceError('not_found', trans('Invalid bid'));
        }

        $ride = $bid->ride;

        if ($ride->user_id != $user->id) {
            return serviceError('unauthorized', trans('This ride is not for this rider'));
        }

        $this->rideRepo->rejectBid($bid);

        event(new EventsRide("rider-driver-$bid->driver_id", 'BID_REJECT', ['ride' => $ride]));

        notify($ride->driver, 'BID_REJECT', [
            'ride_id'         => $ride->uid,
            'amount'          => showAmount($bid->bid_amount),
            'service'         => $ride->service->name,
            'pickup_location' => $ride->pickup_location,
            'destination'     => $ride->destination,
            'duration'        => $ride->duration,
            'distance'        => $ride->distance,
            'pickup_time'     => showDateTime(now()),
        ]);

        return serviceSuccess('rejected_bid', trans('Bid rejected successfully'));
    }

    public function paymentData(int $rideId, int $userId): array
    {
        $ride = $this->rideRepo->findUserRide($rideId, $userId);

        if (!$ride) {
            return serviceError('not_found', trans('Ride not found'));
        }

        $ride = $this->rideRepo->loadRideRelations($ride);

        return serviceSuccess('payment', trans('Ride payments'), [
            'gateways'          => $this->rideRepo->gateways(),
            'image_path'        => getFilePath('gateway'),
            'ride'              => $ride,
            'coupons'           => $this->rideRepo->getActiveCoupons(),
            'driver_image_path' => getFilePath('driver')
        ]);
    }

    public function paymentSave(array $payLoad, int $rideId, int $userId, bool $fromApi): array
    {
        $ride = $this->rideRepo->findUserRide($rideId, $userId);

        if (!$ride) {
            return serviceError('not_found', trans('The ride not found'));
        }

        if ($ride->status == Status::RIDE_COMPLETED) {
            return serviceError('completed', trans('Ride is already completed'));
        }

        if (isApiRequest()) {
            $this->rideRepo->updateTips($ride, $payLoad['tips_amount'] ?? 0);
        }

        return $payLoad['payment_type'] == Status::PAYMENT_TYPE_GATEWAY ? $this->gatewayPayment($ride, $payLoad, $fromApi) : $this->cashPayment($ride);
    }

    public function prepareForReceipt(int $rideId, int $userId): array
    {

        $ride = $this->rideRepo->findUserRide($rideId, $userId);

        if (!$ride) {
            return serviceError('not_exists', trans('The ride is not available'));
        }

        $finalAmount = $ride->amount + $ride->tips_amount - $ride->discount_amount;

        return serviceSuccess('receipt_data', trans('Receipt data prepared'), [
            'ride'              => $ride,
            'final_amount'      => $finalAmount,
            'pdf_generated_at'  => now(),
            'type'              => 'user'
        ]);
    }

    public function makePdf(array $data)
    {
        return Pdf::loadView('admin.rides.pdf', [
            'ride'              => $data['ride'],
            'type'              => $data['type'],
            'finalAmount'       => $data['final_amount'],
            'pdfGeneratedTime'  => $data['pdf_generated_at'],
        ]);
    }

    public function createSos(int $rideId, int $userId, array $payLoad): array
    {
        $ride = $this->rideRepo->findUserRunningRide($rideId, $userId);

        if (!$ride) {
            return serviceError('invalid_ride', trans('The ride is not found'));
        }

        $sosAlert = $this->rideRepo->createSosAlert([
            'ride_id'   => $rideId,
            'latitude'  => $payLoad['latitude'],
            'longitude' => $payLoad['longitude'],
            'message'   => $payLoad['message'] ?? null,
        ]);

        $this->rideRepo->createAdminNotification([
            'user_id'   => $ride->user->id,
            'title'     => 'A new SOS Alert has been created, please take action',
            'click_url' => urlPath('admin.rides.detail', $ride->id),
        ]);

        return serviceSuccess('sos_alert', 'SOS requested successfully', [
            'data'    => $sosAlert,
        ]);
    }

    public function listUserRides(int $userId): array
    {
        $rides = $this->rideRepo->getUserRideList($userId);

        return serviceSuccess('ride_list', trans('Get the ride list'), [
            'rides' => $rides
        ]);
    }

    public function getRideBids(int $rideId, int $userId): array
    {
        $ride = $this->rideRepo->findUserRide($rideId, $userId);

        if (!$ride) {
            return serviceError('not_found', trans('The ride not found'));
        }

        return serviceSuccess('bids', trans('All Bid'), [
            'ride'              => $ride,
            'bids'              => $this->rideRepo->getPendingBidsByRide($ride->id),
            'driver_image_path' => getFilePath('driver'),
            'user_image_path'   => getFilePath('user'),
        ]);
    }

    //controller access end 


    public function detectZones($pickupLat, $pickupLong, $destLat, $destLong): array
    {
        $zones = $this->rideRepo->getActiveZones();

        $pickupZone = $this->findZone($zones, $pickupLat, $pickupLong);

        if (!$pickupZone) {
            return serviceError('not_found', trans('The pickup location is not inside any of our zones'));
        }

        $destinationZone = $this->findZone($zones, $destLat, $destLong);

        if (!$destinationZone) {
            return serviceError('not_found', trans('The destination location is not inside any of our zones'));
        }

        if ($pickupZone->country != $destinationZone->country) {
            return serviceError('not_found', trans('The pickup address and destination address must be within the same country.'));
        }

        return serviceSuccess('inside_zone', trans('Pickup and destination location inside our service zone'), [
            'pickup_zone'      => $pickupZone,
            'destination_zone' => $destinationZone
        ]);
    }

    private function resolveFareFields($pickupZone, $destinationZone): array
    {
        if ($pickupZone->id === $destinationZone->id) {
            return [
                'type'       => Status::CITY_RIDE,
                'min'        => 'city_min_fare',
                'max'        => 'city_max_fare',
                'recommend'  => 'city_recommend_fare',
                'commission' => 'city_fare_commission',
            ];
        }

        return [
            'type'       => Status::INTER_CITY_RIDE,
            'min'        => 'intercity_min_fare',
            'max'        => 'intercity_max_fare',
            'recommend'  => 'intercity_recommend_fare',
            'commission' => 'intercity_fare_commission',
        ];
    }

    private function calculateFareForRideCreation($service, $pickupZone, $destinationZone, $distance): array
    {
        $fare = $this->resolveFareFields($pickupZone, $destinationZone);

        return [
            'ride_type'             => $fare['type'],
            'min_amount'            => getAmount(getRideMiniumAmount($service->{$fare['min']}) * $distance),
            'max_amount'            => getAmount($service->{$fare['max']} * $distance),
            'recommend_amount'      => getAmount($service->{$fare['recommend']} * $distance),
            'commission_percentage' => getAmount($service->{$fare['commission']}),
        ];
    }

    private function calculateFare($pickupZone, $destinationZone, $distance): array
    {
        $fare     = $this->resolveFareFields($pickupZone, $destinationZone);
        $services = $this->rideRepo->getActiveServices();

        $data = [];

        foreach ($services as $service) {
            $data[] = [
                ...$service->toArray(),
                'service_id'       => $service->id,
                'service_name'     => $service->name,
                'min_amount'       => getAmount($service->{$fare['min']} * $distance),
                'max_amount'       => getAmount($service->{$fare['max']} * $distance),
                'recommend_amount' => getAmount($service->{$fare['recommend']} * $distance),
            ];
        }

        return [
            'ride_type' => $fare['type'],
            'services'  => $data
        ];
    }

    private function resolveRouteData(array $data): array
    {
        $zoneData = $this->detectZones(
            $data['pickup_latitude'],
            $data['pickup_longitude'],
            $data['destination_latitude'],
            $data['destination_longitude']
        );

        if ($zoneData['status'] == 'error') {
            return $zoneData;
        }

        $mapData = $this->getGoogleMapData(
            $data['pickup_latitude'],
            $data['pickup_longitude'],
            $data['destination_latitude'],
            $data['destination_longitude']
        );

        if ($mapData['status'] === 'error') {
            return serviceError('api_error', $mapData['message']);
        }

        $pickupZone      = $zoneData['pickup_zone'];
        $destinationZone = $zoneData['destination_zone'];

        if ($pickupZone->country != $destinationZone->country) {
            return serviceError('zone_error', trans('The pickup zone and destination zone must be within the same country.'));
        }

        if ($mapData['distance'] < gs('min_distance')) {
            return serviceError('limit_error', trans('Minimum distance must be :minDistance :distanceUnit.', ['minDistance' => getAmount(gs('min_distance')), 'distanceUnit' => gs('distance_unit') == Status::KM_UNIT ? 'km' : 'Mile']));
        }

        return serviceSuccess('route_ok', trans('Route valid'), [
            'pickup_zone'      => $pickupZone,
            'destination_zone' => $destinationZone,
            'distance'         => $mapData['distance'],
            'duration'         => $mapData['duration'],
        ]);
    }


    private function cashPayment($ride): array
    {
        $ride = $this->rideRepo->markCashPayment($ride);
        $ride = $this->rideRepo->loadRideRelations($ride);

        event(new EventsRide("rider-driver-$ride->driver_id", 'CASH_PAYMENT_REQUEST', [
            'ride' => $ride
        ]));

        event(new EventsRide("rider-user-$ride->user_id", 'CASH_PAYMENT_REQUEST', [
            'ride' => $ride
        ]));

        return serviceSuccess('cash_payment', trans('Please give the driver :rideAmount in cash', ['rideAmount' => showAmount($ride->amount)]), [
            'ride'    => $ride
        ]);
    }

    private function gatewayPayment($ride, array $data, bool $fromApi): array
    {
        $amount = $ride->amount - $ride->discount_amount + $ride->tips_amount;

        $gateway = $this->rideRepo->getGateway($data['method_code'], $data['currency']);

        if (!$gateway) {
            return serviceError('invalid_gateway', trans('Invalid gateway selected'));
        }

        if ($amount < $gateway->min_amount) {
            return serviceError('min_limit', trans('Minimum limit is :minAmount', ['minAmount' => showAmount($gateway->min_amount)]));
        }

        if ($amount > $gateway->max_amount) {
            return serviceError('max_limit', trans('Maximum limit is :maxLimit', ['maxLimit' => showAmount($gateway->max_amount)]));
        }

        $deposit = $this->rideRepo->createDeposit([
            'from_api'        => $fromApi ? 1 : 0,
            'user_id'         => auth()->id(),
            'method_code'     => $gateway->method_code,
            'method_currency' => strtoupper($gateway->currency),
            'amount'          => $amount,
            'charge'          => 0,
            'rate'            => $gateway->rate,
            'final_amount'    => $amount * $gateway->rate,
            'ride_id'         => $ride->id,
            'trx'             => getTrx(),
            'success_url'     => urlPath('user.deposit.history'),
            'failed_url'      => urlPath('user.deposit.history'),
        ]);

        return serviceSuccess('gateway_payment', trans('Online payment'), [
            'deposit'      => $deposit,
            'redirect_url' => route('deposit.app.confirm', encrypt($deposit->id))
        ]);
    }


    private function getGoogleMapData(float $pickupLat, float $pickupLong, float $destLat, float $destLong): array
    {
        $apiKey        = gs('google_maps_api');
        $url           = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$pickupLat},{$pickupLong}&destinations={$destLat},{$destLong}&units=driving&key={$apiKey}";
        $response      = file_get_contents($url);
        $googleMapData = json_decode($response);

        if ($googleMapData->status != 'OK') {
            return [
                'status'  => 'error',
                'message' => 'Something went wrong!'
            ];
        }

        if ($googleMapData->rows[0]->elements[0]->status == 'ZERO_RESULTS') {
            return [
                'status'  => 'error',
                'message' => 'Direction not found'
            ];
        }

        $distance = gs('distance_unit') == Status::MILE_UNIT ? ($googleMapData->rows[0]->elements[0]->distance->value / 1000) * 0.621371 : $googleMapData->rows[0]->elements[0]->distance->value / 1000;

        $duration = $googleMapData->rows[0]->elements[0]->duration->text;

        return [
            'status'              => 'success',
            'distance'            => $distance,
            'duration'            => $duration,
            'origin_address'      => $googleMapData->origin_addresses[0],
            'destination_address' => $googleMapData->destination_addresses[0],
        ];
    }

    protected function findZone($zones, $lat, $long)
    {
        foreach ($zones as $zone) {
            if (insideZone(['lat' => $lat, 'long' => $long], $zone)) {
                return $zone;
            }
        }

        return null;
    }
}
