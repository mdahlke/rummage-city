<?php

use App\User;
use Carbon\Carbon;
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

$factory->define(App\ListingImage::class, function (Faker $faker, $data) {
    usleep(1000);
    $url = $faker->image(storage_path('app/public/seeder'), 1920, 1080, null, false);

    return [
        'id' => Uuid::generate()->string,
        'listing_id' => $data['id'],
        'name' => $faker->name,
        'path' => 'seeder/' . $url,
        'created_at' => $faker->unixTime,
        'updated_at' => $faker->unixTime,
    ];
});
