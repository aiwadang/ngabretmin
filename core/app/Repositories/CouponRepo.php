<?php

namespace App\Repositories;

use App\Constants\Status;
use App\Models\Coupon;
use App\Models\GatewayCurrency;
use App\Models\Ride;
use Illuminate\Database\Eloquent\Collection;

class CouponRepo
{
    public function getActiveCoupons(): Collection
    {
        return Coupon::active()->latest()->get();
    }

    public function findActiveCouponByCode(string $code) : ?Coupon
    {
        return Coupon::active()->where('code', $code)->first();
    }

    public function findUserRideEnd(int $rideId, int $userId) : ?Ride 
    {
        return Ride::where('status', Status::RIDE_END)->where('user_id', $userId)->where('id', $rideId)->first();
    }

    public function getPaymentMethods(): Collection 
    {
        return GatewayCurrency::whereHas('method', fn($q) => $q->active()->automatic())->with('method')->orderBy('method_code')->get();
    }

    public function countCouponUsage(int $couponId, int $userId): int
    {
        return Ride::where('applied_coupon_id', $couponId)
            ->where('user_id', $userId)
            ->whereIn('status', [Status::RIDE_COMPLETED, Status::RIDE_END])
            ->count();
    }

    public function updateRideCoupon(Ride $ride, ?Coupon $coupon, bool $apply = true) : Ride
    {
        if ($apply && $coupon) {
            $discount = $coupon->discount_type == Status::DISCOUNT_PERCENT ? ($ride->amount / 100 * $coupon->amount) : $coupon->amount;

            $ride->applied_coupon_id = $coupon->id;
            $ride->discount_amount   = $discount;
            $ride->commission_amount = ($ride->amount - $discount) / 100 * $ride->commission_percentage;
        } else {
            $ride->applied_coupon_id = 0;
            $ride->discount_amount   = 0;
            $ride->commission_amount = $ride->amount / 100 * $ride->commission_percentage;
        }

        $ride->save();

        return $ride;
    }
}