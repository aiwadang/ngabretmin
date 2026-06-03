<?php

namespace App\Services\Rider;

use App\Repositories\CouponRepo;

class CouponService
{
    protected CouponRepo $couponRepo;

    public function __construct(CouponRepo $couponRepo)
    {
        $this->couponRepo = $couponRepo;
    }

    public function applyCoupon(int $rideId, int $userId, string $code) : array
    {
        $coupon = $this->couponRepo->findActiveCouponByCode($code);

        if (!$coupon) {
            return serviceError('not_found', trans('Coupon not found'));
        }

        $ride = $this->couponRepo->findUserRideEnd($rideId, $userId);

        if (!$ride) {
            return serviceError('not_found', trans('The ride not found'));
        }

        $used = $this->couponRepo->countCouponUsage($coupon->id, $userId);

        if ($used >= $coupon->maximum_using_time) {
            return serviceError('not_available', trans('You are using the maximum time of this coupon'));
        }

        if ($ride->amount < $coupon->minimum_amount) {
            return serviceError('limit', trans('Minimum of :minAmount will be spent on this using this coupon', ['minAmount' => showAmount($coupon->minimum_amount)]));
        }

        $rideAfterApplyingCoupon = $this->couponRepo->updateRideCoupon($ride, $coupon);

        return serviceSuccess('coupon_applied', 'Coupon applied successfully', [
            'ride'            => $rideAfterApplyingCoupon,
            'coupon'          => $coupon,
            'discount_amount' => $rideAfterApplyingCoupon->discount_amount,
            'payment_methods'  => $this->couponRepo->getPaymentMethods()
        ]);
    }

    public function removeCoupon(int $rideId, int $userId) : array
    {
        $ride = $this->couponRepo->findUserRideEnd($rideId, $userId);

        if (!$ride) {
            return serviceError('not_found', trans('The ride not found'));
        }

        if (!$ride->applied_coupon_id) {
            return serviceError('not_available', trans('You have not applied the coupon for this ride'));
        }

        $rideAfterRemovingCoupon = $this->couponRepo->updateRideCoupon($ride, null, false);

        return serviceSuccess('coupon_deleted', 'Coupon deleted successfully', [
            'ride'           => $rideAfterRemovingCoupon,
            'payment_methods' => $this->couponRepo->getPaymentMethods()
        ]);
    }

    public function listCoupons()
    {
        return $this->couponRepo->getActiveCoupons();
    }

    public function userCouponIndex(int $rideId, int $userId) : array
    {
        $ride = $this->couponRepo->findUserRideEnd($rideId, $userId);

        if (!$ride) {
            return serviceError('not_found', trans('Ride not found for applying coupon'));
        }

        return serviceSuccess('coupon_fetched', trans('Coupon fetched successfully'), [
            'ride'    => $ride,
            'coupons' => $this->listCoupons()
        ]);
    }
}