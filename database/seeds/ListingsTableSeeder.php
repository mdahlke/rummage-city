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

//factory(App\Domain::class, 4)
//	->create()
//	->each(function ($domain) {
//		factory(App\Account::class, 1)->states(['rep', 'admin'])
//		                              ->create(['DomainID' => $domain->DomainID])
//		                              ->each(function ($user) use ($domain) {
//			                              $domain->taxBuzzCompanies()
//			                                     ->save(factory(App\TaxBuzzCompany::class)->make());
//			                              $user->save();
//			                              $this->addClients($domain, $user);
//		                              });
//	});

