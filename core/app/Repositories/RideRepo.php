<?php

namespace App\Repositories;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Bid;
use App\Models\Coupon;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\Ride;
use App\Models\RideQueue;
use App\Models\Service;
use App\Models\SosAlert;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Collection;

class RideRepo
{

    public function getActiveZones(): Collection
    {
        return Zone::active()->get();
    }

    public function getActiveServices(): Collection
    {
        return Service::active()->orderBy('name')->get();
    }

    public function hasActiveRide($userId): bool
    {
        return Ride::where('user_id', $userId)->whereIn('status', [Status::RIDE_ACTIVE])->exists();
    }

    public function findActiveService($serviceId): ?Service
    {
        return Service::active()->find($serviceId);
    }

    public function createRide(array $data): Ride
    {
        $ride = new Ride();

        foreach ($data as $key => $value) {
            $ride->{$key} = $value;
        }

        $ride->save();

        if (session()->has('search_details')) {
            session()->forget('search_details');
        }

        return $ride;
    }

    public function minimizeUserPenalty(User $user): void
    {
        $user->penalty_amount = 0;
        $user->save();
    }

    public function createRideQueue(int $rideId): RideQueue
    {
        $rideQueue              = new RideQueue();
        $rideQueue->ride_id     = $rideId;
        $rideQueue->action_type = 'new_driver_notification';
        $rideQueue->ordering    = time();
        $rideQueue->save();

        return $rideQueue;
    }

    public function findUserRideWithRelations(int $rideId, int $userId): ?Ride
    {
        return Ride::with(['bids', 'userReview', 'driverReview', 'driver', 'service', 'driver.vehicle', 'driver.vehicle.model', 'driver.vehicle.color', 'driver.vehicle.year', 'driver.vehicle.brand'])
            ->where('user_id', $userId)
            ->find($rideId);
    }

    public function countCompletedDriverRides(int $driverId, int $excludeRideId): int
    {
        return Ride::where('driver_id', $driverId)->where('id', '!=', $excludeRideId)->where('status', Status::RIDE_COMPLETED)->count();
    }

    public function findCancelableRideByUser(int $rideId, int $userId): ?Ride
    {
        return Ride::whereIn('status', [Status::RIDE_PENDING, Status::RIDE_ACTIVE])->where('user_id', $userId)->find($rideId);
    }

    public function countUserCancellations(int $userId): int
    {
        return Ride::where('user_id', $userId)->where('canceled_user_type', Status::USER)->count();
    }

    public function findPendingBidWithRelations(int $bidId): ?Bid
    {
        return Bid::pending()->with(['ride', 'driver', 'ride.service'])->find($bidId);
    }

    public function rejectBid(Bid $bid): void
    {
        $bid->status = Status::BID_REJECTED;
        $bid->save();
    }

    public function applyUserPenalty(User $user, float $amount): void
    {
        $user->penalty_amount += $amount;
        $user->save();
    }

    public function cancelRide(Ride $ride, string $reason, int $userType): void
    {
        $ride->cancel_reason      = $reason;
        $ride->canceled_user_type = $userType;
        $ride->status             = Status::RIDE_CANCELED;
        $ride->cancelled_at       = now();
        $ride->save();
    }

    public function activateRideFromBid(Bid $bid): Ride
    {
        $ride            = $bid->ride;
        $ride->status    = Status::RIDE_ACTIVE;
        $ride->driver_id = $bid->driver_id;
        $ride->otp       = getNumber(6);
        $ride->amount    = $bid->bid_amount;
        $ride->save();

        return $this->loadRideRelations($ride);
    }

    public function rejectOtherBids(int $acceptedBidId, int $rideId): void
    {
        Bid::where('ride_id', $rideId)->where('id', '!=', $acceptedBidId)->update(['status' => Status::BID_REJECTED]);
    }

    public function acceptBid(Bid $bid): void
    {
        $bid->status      = Status::BID_ACCEPTED;
        $bid->accepted_at = now();
        $bid->save();
    }

    public function userHasRideWithStatus(int $userId, array $statuses): bool
    {
        return Ride::where('user_id', $userId)->whereIn('status', $statuses)->exists();
    }

    public function findPendingBidForUser(int $bidId, int $userId): ?Bid
    {
        return Bid::pending()->with('ride')->whereHas('ride', fn($q) => $q->pending()->where('user_id', $userId))->find($bidId);
    }

    public function gateways(): Collection
    {
        return GatewayCurrency::whereHas('method', fn($q) => $q->active()->automatic())->with('method')->orderBy('method_code')->get();
    }

    public function getActiveCoupons(): Collection
    {
        return Coupon::active()->latest()->get();
    }

    public function loadRideRelations(Ride $ride): Ride
    {
        return $ride->load(
            'driver',
            'driver.vehicle',
            'driver.vehicle.model',
            'driver.vehicle.color',
            'driver.vehicle.year',
            'service',
            'user',
            'coupon',
            'bids',
            'userReview',
            'driverReview'
        );
    }

    public function findUserRide(int $rideId, int $userId): ?Ride
    {
        return Ride::where('user_id', $userId)->find($rideId);
    }

    public function findUserRunningRide(int $rideId, int $userId): ?Ride
    {
        return Ride::running()->where('user_id', $userId)->find($rideId);
    }

    public function createSosAlert(array $data): SosAlert
    {
        $sosAlert = new SosAlert();

        foreach ($data as $key => $value) {
            $sosAlert->{$key} = $value;
        }

        $sosAlert->save();

        return $sosAlert;
    }

    public function createAdminNotification(array $data): AdminNotification
    {
        $adminNotification = new AdminNotification();

        foreach ($data as $key => $value) {
            $adminNotification->{$key} = $value;
        }

        $adminNotification->save();

        return $adminNotification;
    }

    public function updateTips(Ride $ride, float $tips): Ride
    {
        $ride->tips_amount = $tips;
        $ride->save();

        return $ride;
    }

    public function markCashPayment(Ride $ride): Ride
    {
        $ride->payment_status = Status::WAITING_FOR_CASH_PAYMENT;
        $ride->save();

        return $ride;
    }

    public function getGateway(string $methodCode, string $currency): ?GatewayCurrency
    {
        return GatewayCurrency::whereHas('method', fn($q) => $q->active()->automatic())
            ->where('method_code', $methodCode)
            ->where('currency', $currency)
            ->first();
    }

    public function createDeposit(array $data): Deposit
    {
        $deposit = new Deposit();

        foreach ($data as $key => $value) {
            $deposit->{$key} = $value;
        }

        $deposit->save();

        return $deposit;
    }

    public function findCompletedRideForUser(int $rideId, int $userId): ?Ride
    {
        return Ride::with(['user', 'driver'])->where('user_id', $userId)->where('status', Status::RIDE_COMPLETED)->find($rideId);
    }

    public function getUserRideList(int $userId)
    {
        return Ride::with(['driver', 'user', 'service', 'driver.vehicle', 'driver.vehicle.model', 'driver.vehicle.color', 'driver.vehicle.year'])
            ->withCount(['bids' => function ($q) {
                $q->whereIn('status', [Status::BID_PENDING, Status::BID_ACCEPTED]);
            }])
            ->filter(['ride_type', 'status'])
            ->where('user_id', $userId)
            ->orderByRaw('FIELD(status, ' . Status::RIDE_END . ', ' . Status::RIDE_RUNNING . ', ' . Status::RIDE_ACTIVE . ', ' . Status::RIDE_PENDING . ', ' . Status::RIDE_COMPLETED . ', ' . Status::RIDE_CANCELED . ')')
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());
    }

    public function getPendingBidsByRide(int $rideId): Collection
    {
        return Bid::with(['driver', 'driver.service', 'driver.vehicle', 'driver.vehicle.model', 'driver.vehicle.color', 'driver.vehicle.year'])
            ->where('ride_id', $rideId)
            ->where('status', Status::BID_PENDING)
            ->get();
    }
}
