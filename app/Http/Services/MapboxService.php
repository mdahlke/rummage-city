<?php


namespace App\Http\Services;


use GuzzleHttp\Client;

class MapboxService {
    public static $baseUrl = 'https://api.mapbox.com/geocoding/v5';

    public static function reverseGeocode($location) {
        $client = new Client();
        $url = self::$baseUrl . '/mapbox.places/' . urlencode($location) . '.json?access_token=' . config('mapbox.access_token');
        $res = $client->get($url);
        $response = json_decode($res->getBody());

        $feature = $response->features[0] ?? false;

        if ($feature) {

            return (object)[
                'box' => $feature->bbox ?? false,
                'center' => (object)[
                    'lat' => $feature->center[1],
                    'lng' => $feature->center[0]
                ]
            ];
        }

        return [];
    }
}
