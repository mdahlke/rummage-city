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

$factory->define(App\Listing::class, function (Faker $faker, $data = []) {
	$users = $data['users'] ?? [];

	if (!$users) {
		$users = User::all()->pluck('id')->toArray();
	}

	$user = $faker->randomElement($users);

	return [
		'id' => Uuid::generate()->string,
		'user_id' => $user,
		'title' => $faker->text(50),
		'description' => $faker->text(1000),
		'address' => $faker->address,
		'latitude' => $faker->latitude,
		'longitude' => $faker->longitude,
		'ip_address' => $faker->ipv4,
		'created_at' => $faker->unixTime,
		'updated_at' => $faker->unixTime,
	];
});
