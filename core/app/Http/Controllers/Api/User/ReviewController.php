<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Rider\ReviewService;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{

    public function __construct(
        protected ReviewService $reviewService
    ) {}


    public function review()
    {
        $user     = auth()->user();
        $notify[] = 'Rider review list';
        return apiResponse("review", "success", $notify, [
            'user_image_path'   => getFilePath('user'),
            'driver_image_path' => getFilePath('driver'),
            "reviews"           => $this->reviewService->list($user->id),
            'rider'             => $user
        ]);
    }



    public function driverReview($driverId)
    {
        $driver = $this->reviewService->existedDriver($driverId);

        if (!$driver) {
            $notify[] = "The driver is not found";
            return apiResponse('not_found', "error", $notify);
        }

        $notify[] = 'Driver review list';
        return apiResponse("review", "success", $notify, [
            'user_image_path'   => getFilePath('user'),
            'driver_image_path' => getFilePath('driver'),
            "reviews"           => $this->reviewService->driverReviews($driver->id),
            'driver'            => $driver
        ]);
    }



    public function reviewStore(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'review' => 'required',
            'rating' => 'required|in:1,2,3,4,5',
        ]);

        if ($validator->fails()) {
            return apiResponse('validation_error', 'error', $validator->errors()->all());
        }

        $result = $this->reviewService->store(auth()->id(), $id, $request->rating, $request->review);

        return apiResponse($result['remark'], $result['status'], [$result['message']]);
    }
}
