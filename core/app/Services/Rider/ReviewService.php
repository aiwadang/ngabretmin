<?php

namespace App\Services\Rider;

use App\Repositories\ReviewRepo;

class ReviewService
{
    public function __construct(protected ReviewRepo $reviewRepo){}

    public function list(int $riderId, bool $paginate = false)
    {
        return $this->reviewRepo->riderReviews($riderId, $paginate);
    }

    public function existedDriver(int $driverId)
    {
        return $this->reviewRepo->driverExists($driverId);
    } 

    public function driverReviews(int $driverId, bool $avgCalculation = false)
    {
        return $this->reviewRepo->driverReviews($driverId, $avgCalculation);
    }

    public function store(int $userId, int $rideId, int $rating, string $review) : array
    {
        $ride = $this->reviewRepo->validRide($userId, $rideId);

        if (!$ride) {
            return serviceError('invalid', trans('Invalid Ride'));
        }

        if ($this->reviewRepo->reviewExists($ride->id, $ride->driver_id)) {
            return serviceError('already_reviewed', trans('You\'ve already submitted a review & rating for this ride'));
        }

        try {
            $this->reviewRepo->store($ride, $rating, $review, $rider = true, $driver = false);
        } catch (\Exception $exp) {
            return serviceError('review_store_failed', trans('Failed to save review. Please try again.'));
        }

        return serviceSuccess('success', trans('Review placed successfully'));
    }
}