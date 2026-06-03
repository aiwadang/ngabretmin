<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Rider\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller {

    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function index(Request $request) {
        $validate = Validator::make($request->all(), [
            'ride_id' => 'required|integer|gt:0'
        ]);

        if ($validate->fails()) {
            return apiResponse('validation_error', 'error', $validate->errors()->all());
        }

        $result   = $this->couponService->userCouponIndex($request->ride_id, auth()->id());
        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        $html = view('Template::user.ride.coupon_modal_body', ['ride' => $result['ride'], 'coupons' => $result['coupons']])->render();

        return apiResponse($result['remark'], 'success', $notify, [
            'html' => $html
        ]);
    }

    public function apply(Request $request) {
        $validate = Validator::make($request->all(), [
            'ride_id' => 'required|integer|gt:0',
            'code'    => 'required|string'
        ]);

        if ($validate->fails()) {
            return apiResponse('validation_error', 'error', $validate->errors()->all());
        }

        $result   = $this->couponService->applyCoupon($request->ride_id, auth()->id(), $request->code);
        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        $html = view('Template::user.ride.ride_end', ['runningRide' => $result['ride'], 'methods' => $result['payment_methods']])->render();

        return apiResponse($result['remark'], 'success', $notify, [
            'html' => $html
        ]);
    }

    public function remove(Request $request) {
        $validate = Validator::make($request->all(), [
            'ride_id' => 'required|integer|gt:0'
        ]);

        if ($validate->fails()) {
            return apiResponse('validation_error', 'error', $validate->errors()->all());
        } 

        $result   = $this->couponService->removeCoupon($request->ride_id, auth()->id());
        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        $html = view('Template::user.ride.ride_end', ['runningRide' => $result['ride'], 'methods' => $result['payment_methods']])->render();

        return apiResponse($result['remark'], 'success', $notify, [
            'html' => $html
        ]);
    }
}
