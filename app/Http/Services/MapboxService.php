<?php


namespace App\Http\Services;


use App\Geocode;
use App\MapboxFeature;
use GuzzleHttp\Client;

class MapboxService {
    public static $baseUrl = 'https://api.mapbox.com/geocoding/v5';

    public static function reverseGeocode($location): Geocode {
        $geocode = new Geocode();
        $client = new Client();
        $url = self::$baseUrl . '/mapbox.places/' . urlencode($location) . '.json?access_token=' . config('mapbox.access_token');
        $res = $client->get($url);
        $response = json_decode($res->getBody());

        $geocode->setQuery($url);
        $geocode->setDirection('reverse');

        $feature = $response->features[0] ?? false;

        if ($feature) {

            foreach ($response->features as $feature) {

                $f = [
                    'boundingBox' => $feature->bbox ?? false,
                    'center' => (object)[
                        'lat' => $feature->center[1],
                        'lng' => $feature->center[0]
                    ]
                ];

                try {
                    $geocode->addFeature(new MapboxFeature((array)$feature));
                } catch (\Exception $e) {
                    //
                }
            }
        }

        return $geocode;
    }
}
