<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListingsSavedTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('saved_listings', function (Blueprint $table) {
			$table->uuid('id');
			$table->uuid('listing_id');
			$table->uuid('user_id');
			$table->softDeletes();
			$table->timestamps();

			$table->primary(['id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('saved_listings');
	}
}
