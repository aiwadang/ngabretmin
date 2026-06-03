<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\DeviceToken;
use App\Models\GatewayCurrency;
use App\Models\Review;
use App\Models\Ride;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function home()
    {
        $user        = auth()->user();

        if (session()->has('search_details')) {
            $searchData = session()->get('search_details');
            $services   = $this->getServices();

            $pickup = [
                'location'  => isset($searchData['pickup_location']) ? $searchData['pickup_location'] : "",
                'latitude'  => isset($searchData['pickup_latitude']) ? $searchData['pickup_latitude'] : "",
                'longitude' => isset($searchData['pickup_longitude']) ? $searchData['pickup_longitude'] : "",
            ];

            $destination = [
                'location'  => isset($searchData['destination_location']) ? $searchData['destination_location'] : "",
                'latitude'  => isset($searchData['destination_latitude']) ? $searchData['destination_latitude'] : "",
                'longitude' => isset($searchData['destination_longitude']) ? $searchData['destination_longitude'] : "",
            ];

            $rideType        = isset($searchData['ride_type']) ? $searchData['ride_type'] : Status::CITY_RIDE;
            $pageTitle       = "Start a Ride";
            $runningRide     = null;
            $runningRideBids = [];
            $paymentMethods  = $this->getPaymentMethods();

            return view('Template::user.ride.process_ride_step', compact('pageTitle', 'services', 'user', 'runningRide', 'paymentMethods', 'runningRideBids', 'pickup', 'destination', 'rideType'));
        }

        $pageTitle = 'Dashboard';

        $widget    = [
            'totalRides'       => Ride::where('user_id', $user->id)->count(),
            'activeRides'      => Ride::where('user_id', $user->id)->whereNotIn('status', [Status::RIDE_CANCELED, Status::RIDE_COMPLETED])->count(),
            'completedRides'   => Ride::where('user_id', $user->id)->where('status', Status::RIDE_COMPLETED)->count(),
            'canceledRides'    => Ride::where('user_id', $user->id)->where('status', Status::RIDE_CANCELED)->count(),
            'totalDistance'    => Ride::where('user_id', $user->id)->where('status', Status::RIDE_COMPLETED)->sum('distance'),
            'totalAmount'      => Ride::where('user_id', $user->id)->where('status', Status::RIDE_COMPLETED)->sum('amount'),
            'avgRideTime'      => Ride::where('user_id', $user->id)->where('status', Status::RIDE_COMPLETED)->whereNotNull('start_time')->whereNotNull('end_time')->avg(DB::raw('TIMESTAMPDIFF(MINUTE, start_time, end_time)')),
            'totalTransaction' => Transaction::where('user_id', $user->id)->count(),
        ];

        $totalReview             = Review::where('user_id', $user->id)->count();
        $totalReviewPositive     = $totalReview > 0 ? Review::where('user_id', $user->id)->where('rating', '>=', 4)->count() : 0;
        $totalReviewNegative     = $totalReview > 0 ? Review::where('user_id', $user->id)->where('rating', '<=', 3)->count() : 0;
        $totalCurrentMonthReview = $totalReview > 0 ? Review::where('user_id', $user->id)->where('created_at', '>=', now()->startOfMonth())->count() : 0;

        $cardData = [
            'positiveReviewPercentage' => $totalReview > 0 ? ($totalReviewPositive / $totalReview) * 100 : 0,
            'negativeReviewPercentage' => $totalReview > 0 ? ($totalReviewNegative / $totalReview) * 100 : 0,
            'avgRating'                => $user->avg_rating,
            'totalCurrentMonthReview'  => $totalCurrentMonthReview > 0 ? ($totalCurrentMonthReview / $totalReview) * 100 : 0,
            'recentTransactions'       => Transaction::where('user_id', $user->id)->latest()->limit(3)->get(),
            'totalReviews'             => $totalReview,
        ];

        return view('Template::user.dashboard', compact('pageTitle', 'user', 'widget', 'cardData'));
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Payment History';
        $deposits  = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());

        return view('Template::user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function activity()
    {
        $pageTitle         = 'All Activity';
        $runningRideExists = Ride::where('user_id', auth()->id())->whereNotIn('status', [Status::RIDE_CANCELED, Status::RIDE_COMPLETED])->exists();
        $rides             = Ride::with(['driver', 'user', 'service', 'driver.vehicle', 'driver.vehicle.model', 'driver.vehicle.color', 'driver.vehicle.year'])
            ->withCount(['bids' => function ($q) {
                $q->whereIn('status', [Status::BID_PENDING, Status::BID_ACCEPTED]);
            }])
            ->filter(['ride_type', 'status'])
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());

        return view('Template::user.rides', compact('pageTitle', 'rides', 'runningRideExists'));
    }

    public function transactions()
    {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::where('user_id', '!=', 0)->distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function deleteAccount()
    {
        $user             = auth()->user();
        $user->is_deleted = Status::YES;
        $user->save();

        $user->tokens()->delete();

        $notify[] = ['success', 'Account deleted successfully'];
        return back()->withNotify($notify);
    }


    public function userData()
    {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $pageTitle  = 'User Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::user.user_data', compact('pageTitle', 'user', 'countries', 'mobileCode'));
    }


    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $request->validate([
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'username'     => 'required|unique:users|min:6',
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('users')->where('dial_code', $request->mobile_code)],
        ]);


        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        $user->country_code     = $request->country_code;
        $user->mobile           = $request->mobile;
        $user->username         = $request->username;
        $user->address          = $request->address;
        $user->city             = $request->city;
        $user->state            = $request->state;
        $user->zip              = $request->zip;
        $user->country_name     = @$request->country;
        $user->dial_code        = $request->mobile_code;
        $user->profile_complete = Status::YES;
        $user->save();

        return to_route('user.home');
    }

    public function addDeviceToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            return ['success' => true, 'message' => 'Already exists'];
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::NO;
        $deviceToken->save();

        return ['success' => true, 'message' => 'Token saved successfully'];
    }

    public function downloadAttachment($fileHash)
    {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title     = slug(gs('site_name')) . '- attachments.' . $extension;

        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }

        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    private function getPaymentMethods()
    {
        return   GatewayCurrency::whereHas('method', fn($query) => $query->active()->automatic())
            ->with('method')
            ->orderBy('method_code')
            ->get();;
    }
    private function getServices()
    {
        return  Service::active()->orderBy('name')->get();
    }
}
