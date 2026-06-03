<?php

namespace App\Http\Controllers;

use App\Services\GoogleMapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GoogleMapController extends Controller {

    protected $googleMapService;

    public function __construct(GoogleMapService $googleMapService)
    {
        $this->googleMapService = $googleMapService;
    }

    public function suggestedPlaces(Request $request) {
        return $this->googleMapService->getPlacesByInput($request->input, $request->components);
    }


    
    public function getPlaceDetails(Request $request) {
        return $this->googleMapService->getPlaceDetails($request->place_id);
    }

    public function locationByLatLon(Request $request){
        $validate = Validator::make($request->all(), [
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        if ($validate->fails()) {
            return apiResponse('validation_error', 'error', $validate->errors()->all());
        }

        return $this->googleMapService->getLocationByLatLng($request->lat, $request->lng);
    }
}