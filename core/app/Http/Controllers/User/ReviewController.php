<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Rider\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function index()
    {
        $pageTitle = 'Review List';
        $user      = auth()->user();
        $reviews   = $this->reviewService->list($user->id, true);

        return view('Template::user.reviews', compact('pageTitle', 'reviews', 'user'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ride_id' => 'required|integer|gt:0',
            'rating'  => 'required|in:1,2,3,4,5',
            'review'  => 'required|string'
        ]);

        if ($validator->fails()) {
            return apiResponse('validation_error', 'error', $validator->errors()->all());
        }

        $result   = $this->reviewService->store(auth()->id(), $request->ride_id, $request->rating, $request->review);

        $notify[] = $result['message'];
        return apiResponse($result['remark'], $result['status'], $notify, [
            'redirect_url' => route('user.home')
        ]);
    }
}
