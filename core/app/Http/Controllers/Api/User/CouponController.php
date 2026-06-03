<?php

namespace App\Http\Controllers\Api\User;

use App\Constants\Status;
use App\Models\Ride;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Rider\CouponService;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{

    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }


    public function coupons()
    {
        $coupons  = $this->couponService->listCoupons();
        $notify[] = 'Coupon code';
        return apiResponse('coupon', 'success', $notify, $coupons);
    }


    public function applyCoupon(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return apiResponse('validation_error', 'error', $validator->errors()->all());
        }

        $result   = $this->couponService->applyCoupon($id, auth()->id(), $request->coupon_code);
        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        return apiResponse($result['remark'], 'success', $notify, [
            'discount_amount' => $result['discount_amount'],
            'coupon'          => $result['coupon']->coupon,
        ]);
    }



    public function removeCoupon($id)
    {
        $result   = $this->couponService->removeCoupon($id, auth()->id());
        $notify[] = $result['message'];

        if ($result['status'] == 'error') {
            return apiResponse($result['remark'], 'error', $notify);
        }

        return apiResponse($result['remark'], 'success', $notify);
    }
}
