<?php

use App\User;
use Illuminate\Database\Seeder;

class ListingsFondDuLacSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$users = User::all()->pluck('id')->toArray();

		factory(App\Listing::class, 1000)->create()->each(function ($listing) {
		    usleep(10000);
			$number = rand(1, 1);
			$id = $listing->id;
			factory(App\ListingDate::class, $number)->create(['id' => $id]);
			factory(App\ListingImage::class, rand(1,1))->create(['id' => $id]);
		});
	}
}
