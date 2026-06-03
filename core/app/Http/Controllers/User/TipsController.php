<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\GatewayCurrency;
use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipsController extends Controller
{

    public function add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'ride_id'         => 'required|integer|gt:0',
            'amount'          => 'required|numeric|gt:0',
            'predefined_tips' => 'required|boolean'
        ]);


        if ($validate->fails()) {
            return apiResponse('validation_error', 'error', $validate->errors()->all());
        }

        if ($request->predefined_tips) {
            if (!in_array($request->amount, gs('tips_suggest_amount'))) {
                $notify[] = trans('Invalid tips selected');
                return apiResponse('invalid_action', 'error', $notify);
            }
        }

        $ride = Ride::where('user_id', auth()->id())->where('status', Status::RIDE_END)->find($request->ride_id);

        if (!$ride) {
            $notify[] = trans('Ride not found');
            return apiResponse('not_found', 'error', $notify);
        }


        $ride->tips_amount = $request->amount;
        $ride->save();

        $paymentMethods = GatewayCurrency::whereHas('method', fn($query) => $query->active()->automatic())->with('method')->orderBy('method_code')->get();
        $html           = view('Template::user.ride.ride_end', ['runningRide' => $ride, 'methods' => $paymentMethods])->render();

        $notify[] = trans('Tips added successfully');

        return apiResponse('tips_added', 'success', $notify, [
            'html' => $html
        ]);
    }

    public function remove(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'ride_id' => 'required|integer|gt:0'
        ]);

        if ($validate->fails()) {
            return apiResponse('validation_error', 'error', $validate->errors()->all());
        }

        $ride = Ride::where('user_id', auth()->id())->where('status', Status::RIDE_END)->find($request->ride_id);

        if (!$ride) {
            $notify[] = trans('Ride not found');
            return apiResponse('not_found', 'error', $notify);
        }

        $ride->tips_amount = 0;
        $ride->save();

        $paymentMethods = GatewayCurrency::whereHas('method', fn($query) => $query->active()->automatic())->with('method')->orderBy('method_code')->get();
        $html           = view('Template::user.ride.ride_end', ['runningRide' => $ride, 'methods' => $paymentMethods])->render();

        $notify[] = trans('Tips removed successfully');
        return apiResponse('tips_removed', 'success', $notify, [
            'html' => $html
        ]);
    }
}
