<?php

namespace App\Http\Controllers\Api\User;

use App\Constants\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Rider\RideService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RideController extends Controller
{

    protected $rideService;

    public function __construct(RideService $rideService)
    {
        $this->rideService = $rideService;
    }

    public function findFareAndDistance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pickup_latitude'       => 'required|numeric',
            'pickup_longitude'      => 'required|numeric',
            'destination_latitude'  => 'required|numeric',
            'destination_longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return apiResponse("validation_error", 'error', $validator->errors()->all());
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

        return apiResponse($result['remark'], 'success', $notify, [
            'distance'  => $result['distance'],
            'ride_type' => $result['ride_type'],
            'services'  => $result['services'],
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
            'offer_amount'          => 'required|numeric',
            'payment_type'          => ['required', Rule::in(Status::PAYMENT_TYPE_GATEWAY, Status::PAYMENT_TYPE_CASH)],
            'gateway_currency_id'   => $request->payment_type == Status::PAYMENT_TYPE_GATEWAY ? 'required|exists:gateway_currencies,id' : 'nullable',
        ]);

        if ($validator->fails()) {
            return apiResponse("validation_error", 'error', $validator->errors()->all());
        }

        $result   = $this->rideService->createRide($request->all(), auth()->user());
        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        return apiResponse('ride_create_success', 'success', $notify, [
            'ride' => $result['ride']
        ]);
    }

    public function details($id)
    {
        $result   = $this->rideService->getRideDetailsForUser($id, auth()->id());
        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        return apiResponse($result['remark'], 'success', $notify, $result['data']);
    }

    public function cancel(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'cancel_reason' => 'required',
        ]);

        if ($validator->fails()) {
            return apiResponse("validation_error", 'error', $validator->errors()->all());
        }

        $result   = $this->rideService->cancelRide($id, auth()->user(), $request->cancel_reason);
        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        return apiResponse($result['remark'], 'success', $notify);
    }

    public function sos(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'message'   => 'nullable',
        ]);

        if ($validator->fails()) {
            return apiResponse('validation_error', 'error', $validator->errors()->all());
        }

        $result   = $this->rideService->createSos($id, auth()->id(), $request->all());
        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        return apiResponse($result['remark'], 'success', $notify);
    }


    public function list()
    {
        $result   = $this->rideService->listUserRides(auth()->id());
        $notify[] = $result['message'];

        return apiResponse($result['remark'], 'success', $notify, [
            'rides' => $result['rides']
        ]);
    }

    public function bids($id)
    {
        $result   = $this->rideService->getRideBids($id, auth()->id());
        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        return apiResponse($result['remark'], 'success', $notify, [
            'bids'              => $result['bids'],
            'ride'              => $result['ride'],
            'driver_image_path' => $result['driver_image_path'],
            'user_image_path'   => $result['user_image_path'],
        ]);
    }

    public function accept($bidId)
    {
        $result   = $this->rideService->acceptBid($bidId, auth()->user());
        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        return apiResponse($result['remark'], 'success', $notify, [
            'ride' => $result['ride']
        ]);
    }

    public function reject($id)
    {
        $result   = $this->rideService->rejectBid($id, auth()->user());
        $notify[] = $result['message'];

        if ($result['status'] === 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        return apiResponse('rejected_bid', 'success', $notify);
    }

    public function payment($id)
    {
        $result   = $this->rideService->paymentData($id, auth()->id());
        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        return apiResponse($result['remark'], 'success', $notify, [
            'gateways'          => $result['gateways'],
            'image_path'        => $result['image_path'],
            'ride'              => $result['ride'],
            'coupons'           => $result['coupons'],
            'driver_image_path' => $result['driver_image_path'],
        ]);
    }

    public function paymentSave(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_type' => ['required', Rule::in(Status::PAYMENT_TYPE_GATEWAY, Status::PAYMENT_TYPE_CASH)],
            'method_code'  => 'required_if:payment_type,1',
            'currency'     => 'required_if:payment_type,1',
            'tips_amount'  => 'required|numeric|gte:0'
        ]);

        if ($validator->fails()) {
            return apiResponse("validation_error", 'error', $validator->errors()->all());
        }

        $result   = $this->rideService->paymentSave($request->all(), $id, auth()->id(), true);
        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        if ($result['remark'] == 'cash_payment') {
            return apiResponse($result['remark'], 'success', $notify, [
                'ride' => $result['ride']
            ]);
        }

        return apiResponse($result['remark'], 'success', $notify, [
            'deposit'      => $result['deposit'],
            'redirect_url' => $result['redirect_url']
        ]);
    }

    public function receipt($id)
    {
        $result = $this->rideService->prepareForReceipt($id, auth()->id());

        if ($result['status'] == 'error') {
            $notify[] = $result['message'];
            return apiResponse($result['remark'], 'error', $notify);
        }

        $pdf = $this->rideService->makePdf($result);

        return $pdf->stream('ride.pdf');
    }
}
