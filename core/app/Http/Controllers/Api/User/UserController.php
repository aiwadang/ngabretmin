<?php

namespace App\Http\Controllers\Api\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\GatewayCurrency;
use App\Models\NotificationLog;
use App\Models\Review;
use App\Models\Ride;
use App\Models\RidePayment;
use App\Models\Service;
use App\Models\Transaction;
use App\Rules\FileTypeValidate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function dashboard()
    {
        $notify[]    = 'User Dashboard';
        $services    = Service::active()->orderBy('name')->get();
        $user        = auth()->user();
        $runningRide = Ride::running()->where('user_id', $user->id)->with(['user', 'driver.vehicle', 'driver.vehicle.model', 'driver.vehicle.color', 'driver.vehicle.year'])->first();

        $paymentMethod = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->active()->automatic();
        })->with('method')->orderby('method_code')->get();

        return apiResponse("dashboard", "success", $notify, [
            'user'               => $user,
            'payment_method'     => $paymentMethod,
            'services'           => $services,
            'running_ride'       => $runningRide,
            'service_image_path' => getFilePath('service'),
            'gateway_image_path' => getFilePath('gateway'),
            'user_image_path'    => getFilePath('user'),
        ]);
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            $notify[] = 'You\'ve already completed your profile';
            return apiResponse("already_completed", "error", $notify);
        }

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));



        $validator = Validator::make($request->all(), [
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'username'     => 'required|unique:users|min:6',
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('users')->where('dial_code', $request->mobile_code)],
        ]);

        if ($validator->fails()) {
            return apiResponse("validation_error", "error", $validator->errors()->all());
        }


        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = 'No special character, space or capital letters in username';
            return apiResponse("validation_error", "error", $notify);
        }


        $user->country_code = $request->country_code;
        $user->mobile       = $request->mobile;
        $user->username     = $request->username;

        $user->address      = $request->address;
        $user->city         = $request->city;
        $user->state        = $request->state;
        $user->zip          = $request->zip;
        $user->country_name = @$request->country;
        $user->dial_code    = $request->mobile_code;

        $user->profile_complete = Status::YES;
        $user->save();

        $notify[] = 'Profile completed successfully';

        return apiResponse("profile_completed", "success", $notify, [
            'user' => $user
        ]);
    }

    public function paymentHistory()
    {
        $payments = RidePayment::where('rider_id', auth()->id())->orderBy('id', 'desc')->with('rider', 'ride', 'driver')->paginate(getPaginate());
        $notify[] = 'Payment Data';
        return apiResponse("payments", "success", $notify, [
            'payments' => $payments,
        ]);
    }

    public function transactions(Request $request)
    {
        $remarks      = Transaction::distinct('remark')->get('remark');
        $transactions = Transaction::where('user_id', auth()->id());

        if ($request->search) {
            $transactions = $transactions->where('trx', $request->search);
        }

        if ($request->type) {
            $type         = $request->type == 'plus' ? '+' : '-';
            $transactions = $transactions->where('trx_type', $type);
        }

        if ($request->remark) {
            $transactions = $transactions->where('remark', $request->remark);
        }

        $transactions = $transactions->orderBy('id', 'desc')->paginate(getPaginate());
        $notify[]     = 'Transactions data';

        return apiResponse("transactions", "success", $notify, [
            'transactions' => $transactions,
            'remarks'      => $remarks,
        ]);
    }

    public function submitProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname'  => 'required',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ], [
            'firstname.required' => 'The first name field is required',
            'lastname.required'  => 'The last name field is required'
        ]);

        if ($validator->fails()) {
            return apiResponse("validation_error", "error", $validator->errors()->all());
        }



        $user            = auth()->user();
        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->address   = $request->address;
        $user->city      = $request->city;
        $user->state     = $request->state;
        $user->zip       = $request->zip;

        if ($request->hasFile('image')) {
            try {
                $user->image = fileUploader($request->image, getFilePath('user'), getFileSize('user'), $user->image);
            } catch (\Exception $exp) {
                $notify[] = 'Couldn\'t upload your image';
                return apiResponse('exception', 'error', $notify);
            }
        }

        $user->save();

        $notify[] = 'Profile updated successfully';

        return apiResponse("profile_updated", "success", $notify);
    }

    public function submitPassword(Request $request)
    {
        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', $passwordValidation]
        ]);

        if ($validator->fails()) {
            return apiResponse("validation_error", "error", $validator->errors()->all());
        }

        $user = auth()->user();
        if (Hash::check($request->current_password, $user->password)) {
            $password       = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            $notify[] = 'Password changed successfully';
            return apiResponse("password_changed", "success", $notify);
        } else {
            $notify[] = 'The password doesn\'t match!';
            return apiResponse("not_match", "validation_error", $notify);
        }
    }

    public function addDeviceToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            return apiResponse("validation_error", "error", $validator->errors()->all());
        }

        $userId      = auth()->user()->id;
        $deviceToken = DeviceToken::where('token', $request->token)->where('user_id', $userId)->first();

        if ($deviceToken) {
            $notify[] = 'Token already exists';
            return apiResponse("token_exists", "error", $notify);
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::YES;
        $deviceToken->save();

        $notify[] = 'Token saved successfully';
        return apiResponse("token_saved", "success", $notify);
    }



    public function pushNotifications()
    {
        $notifications = NotificationLog::where('user_id', auth()->id())->where('sender', 'firebase')->orderBy('id', 'desc')->paginate(getPaginate());
        $notify[]      = 'Push notifications';
        return apiResponse("notifications", "success", $notify, [
            'notifications' => $notifications,
        ]);
    }


    public function pushNotificationsRead($id)
    {
        $notification = NotificationLog::where('user_id', auth()->id())->where('sender', 'firebase')->find($id);
        if (!$notification) {
            $notify[] = 'Notification not found';
            return apiResponse("notification_not_found", "error", $notify);
        }
        $notify[]                = 'Notification marked as read successfully';
        $notification->user_read = 1;
        $notification->save();

        return apiResponse("notification_read", "success", $notify);
    }


    public function userInfo()
    {
        $notify[] = 'User information';
        return apiResponse("user_info", "success", $notify, [
            'user'       => auth()->user(),
            'image_path' => getFilePath('user')
        ]);
    }

    public function deleteAccount()
    {
        $user             = auth()->user();
        $user->is_deleted = Status::YES;
        $user->save();

        $user->tokens()->delete();

        $notify[] = 'Account deleted successfully';
        return apiResponse("account_deleted", "success", $notify);
    }

    public function pusher($socketId, $channelName)
    {
        $general      = gs();
        $pusherSecret = $general->pusher_config->app_secret;
        $str          = $socketId . ":" . $channelName;
        $hash         = hash_hmac('sha256', $str, $pusherSecret);

        return response()->json([
            'auth'    => $general->pusher_config->app_key . ":" . $hash,
        ]);
    }

    public function review($driverId)
    {
        $notify[] = 'Driver Review List';
        return apiResponse("review", "success", $notify, [
            'user_image_path'   => getFilePath('user'),
            'driver_image_path' => getFilePath('driver'),
            "reviews"           => Review::with("user")->latest('id')->where('driver_id', $driverId)->get()
        ]);
    }

    public function getLocationByLatLng()
    {

        $validator = Validator::make(request()->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->fails()) {
            return googleFormatResponse([
                'error_message' => $validator->errors()->first(),
                'results' => [],
                'status' => 'validation_error'
            ]);
        }

        try {

            $lat = request()->lat;
            $lng = request()->lng;

            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'latlng' => "{$lat},{$lng}",
                'key' => gs('google_maps_api'),
            ]);

            $data = $response->json();
            return googleFormatResponse($data);
        } catch (Exception $exp) {
            $message = $exp->getMessage() ?? "Something went wrong, please try again later";

            return googleFormatResponse([
                'error_message' => $message,
                'results' => [],
                'status' => 'REQUEST_DENIED'
            ]);
        }
    }
    public function searchAddressByLocationName()
    {

        $validator = Validator::make(request()->all(), [
            'input'        => 'required|string',
            'country_code' => 'nullable|string',
            'lat'          => 'nullable|string',
            'lng'          => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return googleFormatResponse([
                'error_message' => $validator->errors()->first(),
                'results' => [],
                'status' => 'validation_error'
            ]);
        }

        try {

            $params = [
                'input'      => request('input'),
                'key'        => gs('google_maps_api'),
            ];

            if (request('country_code') && !is_null(request('country_code'))) {
                $params['components'] = 'country:' . request('country_code');
            } else {
                if (request('lat') && !is_null(request('lat') && request('lng') && !is_null(request('lng')))) {
                    $params['location'] = request('lat') . ',' . request('lng');
                    $params['radius']   = "200000";
                }
            }

            $response = Http::get('https://maps.googleapis.com/maps/api/place/autocomplete/json', $params);
            $data = $response->json();

            return response()->json($data);
        } catch (Exception $exp) {
            $message = $exp->getMessage() ?? "Something went wrong, please try again later";

            return response()->json([
                'error_message' => $message,
                'results' => [],
                'status' => 'REQUEST_DENIED'
            ]);
        }
    }
    public function getPlaceDetails()
    {

        $validator = Validator::make(request()->all(), [
            'place_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return googleFormatResponse([
                'error_message' => $validator->errors()->first(),
                'results' => [],
                'status' => 'validation_error'
            ]);
        }
        try {

            $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                'placeid' => request('place_id'),
                'key' => gs('google_maps_api'),
            ]);

            $data = $response->json();
            return response()->json($data);
        } catch (Exception $exp) {
            $message = $exp->getMessage() ?? "Something went wrong, please try again later";

            return response()->json([
                'error_message' => $message,
                'results' => [],
                'status' => 'REQUEST_DENIED'
            ]);
        }
    }
    public function getDirection()
    {

        $validator = Validator::make(request()->all(), [
            'origin'      => 'required|string',
            'destination' => 'required|string',
        ]);

        if ($validator->fails()) {
            return googleFormatResponse([
                'error_message' => $validator->errors()->first(),
                'results' => [],
                'status' => 'validation_error'
            ]);
        }
        try {

            $response = Http::get('https://maps.googleapis.com/maps/api/directions/json', [
                'origin'      => request('origin'),
                'destination' => request('destination'),
                'mode'        => 'driving',
                'key'         => gs('google_maps_api'),
            ]);

            $data = $response->json();
            return response()->json($data);
        } catch (Exception $exp) {
            $message = $exp->getMessage() ?? "Something went wrong, please try again later";
            return response()->json([
                'error_message' => $message,
                'results' => [],
                'status' => 'REQUEST_DENIED'
            ]);
        }
    }

    public function getDirectionByRouteAPIV2()
    {
        $validator = Validator::make(request()->all(), [
            'pickup_latitude'       => 'required|string',
            'pickup_longitude'      => 'required|string',
            'destination_latitude'  => 'required|string',
            'destination_longitude' => 'required|string',
        ]);

        if ($validator->fails()) {
            return googleFormatResponse([
                'error_message' => $validator->errors()->first(),
                'results' => [],
                'status' => 'validation_error'
            ]);
        }

        try {
            $response = Http::withHeaders([
                'X-Goog-Api-Key' => gs('google_maps_api'),
                'X-Goog-FieldMask' => 'routes.duration,routes.distanceMeters,routes.polyline'
            ])->post('https://routes.googleapis.com/directions/v2:computeRoutes', [
                'origin' => [
                    'location' => [
                        'latLng' => [
                            'latitude'  => request('pickup_latitude'),
                            'longitude' => request('pickup_longitude'),
                        ]
                    ]
                ],
                'destination' => [
                    'location' => [
                        'latLng' => [
                            'latitude'  => request('destination_latitude'),
                            'longitude' => request('destination_longitude'),
                        ]
                    ]
                ],
                'travelMode'               => 'DRIVE',
                'routingPreference'        => 'TRAFFIC_AWARE',
                'computeAlternativeRoutes' => false,
                'languageCode'             => 'en-US',
                'units'                    => 'METRIC'
            ]);

            $data = $response->json();

            return response()->json($data);
        } catch (Exception $exp) {
            return response()->json([
                'error_message' => $exp->getMessage() ?? 'Something went wrong, please try again later',
                'results' => [],
                'status' => 'REQUEST_DENIED'
            ]);
        }
    }
}
