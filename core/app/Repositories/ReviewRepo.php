<?php

namespace App\Repositories;

use App\Models\Driver;
use App\Models\Review;
use App\Models\Ride;
use Illuminate\Database\Eloquent\Collection;

class ReviewRepo
{
    public function riderReviews(int $riderId, bool $paginate = false)
    {
        $query = Review::where('user_id', $riderId)->with('ride.driver')->latest();
        return $paginate ? $query->paginate(getPaginate()) : $query->get();
    }

    public function driverReviews(int $driverId, bool $avgCalculation = false): Collection
    {
        if ($avgCalculation) {
            return Review::where('driver_id', $driverId)->get();
        }

        return Review::where('driver_id', $driverId)->with('ride:id,user_id', 'ride.user')->latest()->get();
    }

    public function riderReview(int $userId, bool $avgCalculation = false): Collection
    {
        if ($avgCalculation) {
            return Review::where('user_id', $userId)->get();
        }

        return Review::latest('id')->where('user_id', $userId)->with(['ride:id,driver_id','ride.driver'])->get();
    }

    public function driverExists(int $driverId): ?Driver
    {
        return Driver::active()->where('id', $driverId)->with('vehicle')->first();
    }

    public function reviewExists(int $rideId, int $driverId): bool
    {
        return Review::where('ride_id', $rideId)->where('driver_id', $driverId)->exists();
    }

    public function validRide(int $userId, int $rideId): ?Ride
    {
        return Ride::completed()->where('user_id', $userId)->with('driver')->find($rideId);
    }

    public function store(Ride $ride, int $rating, string $reviewText, bool $rider, bool $driver): ?Review
    {
        if (!$rider && !$driver) {
            return null;
        }

        $review          = new Review();
        $review->ride_id = $ride->id;
        $review->rating  = $rating;
        $review->review  = $reviewText;

        if ($rider) {
            $review->user_id   = 0;
            $review->driver_id = $ride->driver_id;
        } else {
            $review->user_id   = $ride->user_id;
            $review->driver_id = 0;
        }

        $review->save();

        if ($rider) {
            $this->updateDriverStats($ride);
        } else {
            $this->updateUserStats($ride);
        }

        return $review;
    }

    protected function updateDriverStats(Ride $ride): void
    {
        $reviews = $this->driverReviews($ride->driver_id, true);

        $driver                = $ride->driver;
        $driver->avg_rating    = $reviews->avg('rating');
        $driver->total_reviews = $reviews->count();
        $driver->save();
    }

    protected function updateUserStats(Ride $ride): void
    {
        $reviews = $this->riderReview($ride->user_id, true);

        $user                = $ride->user;
        $user->avg_rating    = $reviews->avg('rating');
        $user->total_reviews = $reviews->count();
        $user->save();
    }
}