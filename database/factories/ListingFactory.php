<?php

use App\User;
use Faker\Generator as Faker;
use Webpatser\Uuid\Uuid;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

function random_float($min, $max) {
    return ($min + lcg_value() * (abs($max - $min)));
}

$factory->define(App\Listing::class, function (Faker $faker, $data = []) {
    $users = $data['users'] ?? [];

    if (!$users) {
        $users = User::all()->pluck('id')->toArray();
    }

    $user = $faker->randomElement($users);

    $minLng = -124.615587;
    $maxLng = -68.541368;
    $maxLat = 48.502460;
    $minLat = 26.175718;

    $house = $faker->buildingNumber;
    $street = $faker->streetName;
    $city = $faker->city;
    $state = $faker->stateAbbr;
    $postcode = $faker->postcode;

    $lat = random_float($minLat, $maxLat);
    $lng = random_float($minLng, $maxLng);

    return [
        'id' => Uuid::generate()->string,
        'user_id' => $user,
        'title' => $faker->text(50),
        'description' => $faker->text(1000),
        'house_number' => $house,
        'street_name' => $street,
        'city' => $city,
        'state' => $state,
        'country_code' => 'US',
        'postcode' => $postcode,
        'address' => $house . ' ' . $street . ' ' . $city . ', ' . $state . ' ' . $postcode,
        'latitude' => $lat,
        'longitude' => $lng,
        'ip_address' => $faker->ipv4,
        'created_at' => $faker->unixTime,
        'updated_at' => $faker->unixTime,
    ];
});
