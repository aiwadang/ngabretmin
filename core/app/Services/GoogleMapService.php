<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleMapService
{


    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = gs('google_maps_api');
        $this->baseUrl = 'https://maps.googleapis.com/maps/api/';
    }

    /**
     * General method to perform GET requests to Google API endpoints.
     **/
    private function get(string $endpoint, array $payLoads = []): array
    {
        $payLoads = array_merge($payLoads, [
            'key'      => $this->apiKey,
            'language' => 'en'
        ]);

        try {
            $response = Http::timeout(15)->connectTimeout(10)->get($this->baseUrl . $endpoint, $payLoads);
        } catch (\Exception $exp) {
            return serviceError('connection_error', trans('Unable to connect to Google API. Please check internet or SSL configuration.'));
        }

        if (!$response->successful()) {
            return serviceError('failed', trans('Failed to connect to Google API'));
        }

        $data = $response->json();

        if (($data['status'] ?? null) != 'OK') {
            $message = $data['error_message'] ?? 'Google API error: ' . ($data['status'] ?? 'UNKNOWN');
            return serviceError('place_not_found', trans($message));
        }

        return serviceSuccess('success', trans('Response fetched successfully'), [
            'data' => $data
        ]);
    }

    /**
     * Get location details by latitude and longitude.
     **/
    public function getLocationByLatLng(float $lat, float $lng): array
    {
        try {
            $response = $this->get('geocode/json', [
                'latlng' => $lat . ',' . $lng,
            ]);

            if ($response['status'] == 'error') {
                return $response;
            }

            $results = $response['data']['results'] ?? [];

            $countryCode = null;
            $city        = null;
            $foundResult = null;

            foreach ($results as $res) {
                if (!isset($res['address_components']) || !is_array($res['address_components'])) {
                    continue;
                }

                foreach ($res['address_components'] as $comp) {
                    if (in_array('country', $comp['types'])) {
                        $countryCode = strtolower($comp['short_name'] ?? '');
                    }

                    $cityTypes = ['locality', 'administrative_area_level_2', 'administrative_area_level_1', 'postal_town'];
                    if (array_intersect($comp['types'] ?? [], $cityTypes)) {
                        $city = $comp['long_name'] ?? null;
                    }
                }

                if ($countryCode && $city) {
                    $foundResult = $res;
                    break;
                }
            }

            $result = $foundResult ?: ($results[0] ?? null);

            if (!$result) {
                return serviceError('not_found', trans('No valid results found for these coordinates.'));
            }

            return serviceSuccess($response['remark'], trans('Location found successfully'), [
                'data' => [
                    'lat'               => $lat,
                    'lng'               => $lng,
                    'countryCode'       => $countryCode,
                    'city'              => $city,
                    'formatted_address' => $result['formatted_address'] ?? 'N/A',
                    'location'          => $result['geometry']['location'] ?? null,
                ]
            ]);
        } catch (\Exception $e) {
            return serviceError('processing_error', trans('Something went wrong while processing location data.'));
        }
    }

    /**
     * Get place suggestions by input search string.
     **/
    public function getPlacesByInput(string $input, ?string $components = null): array
    {
        $payLoads = [
            'input'  => $input
        ];

        if ($components) {
            $payLoads['components'] = $components;
        }

        $response = $this->get('place/autocomplete/json', $payLoads);

        if ($response['status'] == 'error') {
            return $response;
        }

        return serviceSuccess($response['remark'], trans('Location suggestion fetched successfully.'), [
            'data' => [
                'predictions' => $response['data']['predictions'] ?? []
            ]
        ]);
    }

    /**
     * Get detailed information of a place by place ID.
     **/
    public function getPlaceDetails(string $placeId): array
    {
        $response = $this->get('place/details/json', [
            'place_id' => $placeId,
            'fields'   => 'geometry,formatted_address',
        ]);

        if ($response['status'] == 'error') {
            return $response;
        }

        $result = $response['data']['result'] ?? null;

        if (!$result) {
            return serviceError('not_found', trans('Place details not found'));
        }

        return serviceSuccess('place_details_found', trans('Place details found successfully'), [
            'data' => [
                'formatted_address' => $result['formatted_address'],
                'location'          => $result['geometry']['location'],
                'viewport'          => $result['geometry']['viewport'],
            ]
        ]);
    }
}
