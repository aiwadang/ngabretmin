<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\GatewayCurrency;
use App\Models\Ride;
use App\Models\Service;
use App\Services\Rider\RideService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RideController extends Controller
{
    protected $rideService;

    private $bidRelations = [
        'user',
        'driver',
        'driver.vehicle',
        'driver.vehicle.model',
        'driver.vehicle.color',
        'driver.vehicle.year',
        'driver.vehicle.brand'
    ];

    public function __construct(RideService $rideService)
    {
        $this->rideService = $rideService;
    }

    public function processRideStep()
    {
        $pageTitle   = 'Complete Your Ride';
        $services    = Service::active()->orderBy('name')->get();
        $user        = auth()->user();
        $runningRide = Ride::whereNotIn('status', [Status::RIDE_CANCELED, Status::RIDE_COMPLETED])
            ->where('user_id', $user->id)
            ->with($this->bidRelations)
            ->first();

        $paymentMethods = GatewayCurrency::whereHas('method', fn($query) => $query->active()->automatic())
            ->with('method')
            ->orderBy('method_code')
            ->get();

        if ($runningRide) {
            $runningRideBids = Bid::where('ride_id', $runningRide->id)
                ->pending()
                ->whereHas('ride', fn($q) => $q->where('user_id', $user->id))
                ->with(['driver', 'driver.vehicle', 'driver.vehicle.model', 'driver.vehicle.color', 'driver.vehicle.year', 'driver.vehicle.brand'])
                ->get();
        } else {
            $runningRideBids = [];
        }

        if ($runningRide) {
            return view('Template::user.ride.process_ride_step', compact('pageTitle', 'services', 'user', 'runningRide', 'paymentMethods', 'runningRideBids'));
        }

        $searchData = [];

        if (session()->has('search_details')) {
            $searchData = session()->get('search_details');
        }

        $pickup = [
            'location'  => isset($searchData['pickup_location']) ? $searchData['pickup_location'] : "",
            'latitude'  => isset($searchData['pickup_latitude']) ? $searchData['pickup_latitude'] : "",
            'longitude' => isset($searchData['pickup_longitude']) ? $searchData['pickup_longitude'] : "",
        ];

        $destination = [
            'location'  => isset($searchData['destination_location']) ? $searchData['destination_location'] : "",
            'latitude'  => isset($searchData['destination_latitude']) ? $searchData['destination_latitude'] : "",
            'longitude' => isset($searchData['destination_longitude']) ? $searchData['destination_longitude'] : "",
        ];

        $rideType = isset($searchData['ride_type']) ? $searchData['ride_type'] : Status::CITY_RIDE;

        return view('Template::user.ride.process_ride_step', compact('pageTitle', 'services', 'user', 'runningRide', 'paymentMethods', 'runningRideBids', 'pickup', 'destination', 'rideType'));
    }

    public function findAreaAndDistance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pickup_latitude'       => 'required|numeric',
            'pickup_longitude'      => 'required|numeric',
            'destination_latitude'  => 'required|numeric',
            'destination_longitude' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return apiResponse('validation_error', 'error', $validator->errors()->all());
        }

        $result = $this->rideService->getFareAndRouteData(
            $request->pickup_latitude,
            $request->pickup_longitude,
            $request->destination_latitude,
            $request->destination_longitude
        );


        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        $services        = $result['services'];
        $pickUpZone      = $result['pickup_zone'];
        $destinationZone = $result['destination_zone'];
        $rideType        = $pickUpZone->id == $destinationZone->id ? Status::CITY_RIDE : Status::INTER_CITY_RIDE;
        $distance        = $result['distance'];

        return apiResponse($result['remark'], 'success', $notify, [
            'data'      => $services,
            'html'      => view('Template::user.ride.services', compact('services', 'rideType', 'distance'))->render(),
            'ride_type' => $result['ride_type'],
            'distance'  => $distance
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id'            => 'required|integer',
            'pickup_latitude'       => 'required|numeric',
            'pickup_longitude'      => 'required|numeric',
            'pickup_location'       => 'required|string',
            'destination_latitude'  => 'required|numeric',
            'destination_longitude' => 'required|numeric',
            'destination_location'  => 'required|string',
            'note'                  => 'nullable',
            'number_of_passenger'   => 'required|integer',
            'offer_amount'          => 'required|numeric|gt:0',
            'payment_type'          => ['required', Rule::in(Status::PAYMENT_TYPE_GATEWAY, Status::PAYMENT_TYPE_CASH)],
            'gateway_currency_id'   => $request->payment_type == Status::PAYMENT_TYPE_GATEWAY ? 'required|exists:gateway_currencies,id' : 'required|in:cash',
        ]);

        if ($validator->fails()) {
            return apiResponse('validation_error', 'error', $validator->errors()->all());
        }

        $result   = $this->rideService->createRide($request->all(), auth()->user());
        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify, [
                'offer_amount'        => getAmount($request->offer_amount),
                'number_of_passenger' => $request->number_of_passenger
            ]);
        }

        $ride = $result['ride'];

        return apiResponse($result['remark'], 'success', $notify, [
            'location_html'    => view('Template::user.ride.running_ride_location', ['runningRide' => $ride])->render(),
            'ride_detail_html' => view('Template::user.ride.ride_detail', ['runningRide' => $ride])->render()
        ]);
    }

    public function cancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ride_id'       => 'required|integer|gt:0',
            'cancel_reason' => 'required|string'
        ]);

        if ($validator->fails()) {
            return apiResponse('validation_error', 'error', $validator->errors()->all());
        }

        $result = $this->rideService->cancelRide($request->ride_id, auth()->user(), $request->cancel_reason);

        $notify[] = $result['message'];
        return apiResponse($result['remark'], $result['status'], $notify);
    }


    public function reject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bid_id' => 'required|integer|gt:0'
        ]);

        if ($validator->fails()) {
            return apiResponse('validation_error', 'error', $validator->errors()->all());
        }

        $result = $this->rideService->rejectBid($request->bid_id, auth()->user());

        $notify[] = $result['message'];
        return  apiResponse($result['remark'], $result['status'], $notify);
    }

    public function accept(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bid_id' => 'required|integer|gt:0'
        ]);

        if ($validator->fails()) {
            return apiResponse('validation_error', 'error', $validator->errors()->all());
        }

        $result   = $this->rideService->acceptBid($request->bid_id, auth()->user());
        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        return apiResponse($result['remark'], 'success', $notify, [
            'html' => view('Template::user.ride.active_ride', ['ride' => $result['ride']])->render()
        ]);
    }

    public function payment(Request $request)
    {
        $request->validate([
            'ride_id' => 'required|integer|gt:0'
        ]);

        $result = $this->rideService->paymentData($request->ride_id, auth()->id());

        if ($result['status'] == 'error') {
            return notifyBack('error', $result['message']);
        }

        $pageTitle = 'Payment Gateways';

        return view('Template::user.payment.index', [
            'pageTitle' => $pageTitle,
            'gateways'  => $result['gateways'],
            'ride'      => $result['ride'],
            'coupons'   => $result['coupons'],
        ]);
    }

    public function paymentSave(Request $request)
    {
        $request->validate([
            'ride_id'      => 'required|integer|gt:0',
            'payment_type' => 'required|in:' . Status::PAYMENT_TYPE_GATEWAY . ',' . Status::PAYMENT_TYPE_CASH,
            'method_code'  => 'required_if:payment_type,' . Status::PAYMENT_TYPE_GATEWAY,
            'currency'     => 'required_if:payment_type,' . Status::PAYMENT_TYPE_GATEWAY,
        ]);

        $result = $this->rideService->paymentSave($request->all(), $request->ride_id, auth()->id(), false);

        if ($result['status'] == 'error') {
            return notifyBack('error', $result['message']);
        }

        if ($result['remark'] == 'cash_payment') {
            $notify[] = ['success', $result['message']];
            return to_route('user.ride.process.step')->withNotify($notify);
        }

        session()->put('Track', $result['deposit']->trx);
        return to_route('user.deposit.confirm');
    }

    public function receipt($id)
    {
        $result = $this->rideService->prepareForReceipt($id, auth()->id());

        if ($result['status'] == 'error') {
            return notifyBack('error', $result['message']);
        }

        $pdf = $this->rideService->makePdf($result);

        return $pdf->stream('ride.pdf');
    }

    public function details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ride_id'   => 'required|integer|gt:0',
        ]);

        info($request->ride_id);

        if ($validator->fails()) {
            return apiResponse('validation_error', 'error', $validator->errors()->all());
        }

        $result = $this->rideService->getRideDetailsForUser($request->ride_id, auth()->id());
        $notify = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        return apiResponse($result['remark'], 'success', $notify, [
            'html' => view('Template::user.ride.ride_detail_modal_content', ['ride' => $result['data']['ride']])->render()
        ]);
    }
}
