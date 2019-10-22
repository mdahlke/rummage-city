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

$factory->define(App\ListingDate::class, function (Faker $faker, $data) {
	$fakeStart = ($faker->dateTimeBetween('now', '+1 year'));

	$start = new Carbon($fakeStart->format('m/d/Y H:i:s'));
	$end = clone $start;
	$add = rand(3, 8);
	$end = $end->addHours($add);

	return [
		'id' => Uuid::generate()->string,
		'listing_id' => $data['id'],
		'start' => $start,
		'end' => $end,
		'created_at' => $faker->unixTime,
		'updated_at' => $faker->unixTime,
	];
});
