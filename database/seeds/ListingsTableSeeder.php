<?php

use App\User;
use Illuminate\Database\Seeder;

class ListingsTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$users = User::all()->pluck('id')->toArray();

		factory(App\Listing::class, 500)->create()->each(function ($listing) {
			$number = rand(1, 5);
			$id = $listing->id;
			factory(App\ListingDate::class, $number)->create(['id' => $id]);
		});
	}
}
