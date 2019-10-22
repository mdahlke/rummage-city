<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListingsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('listings', function (Blueprint $table) {
			$table->uuid('id');
			$table->uuid('user_id');
			$table->string('title');
			$table->text('description');
			$table->string('address');
			//            $table->point('coordinates');
			$table->string('latitude', 20);
			$table->string('longitude', 20);
			$table->ipAddress('ip_address');
			$table->timestamps();
			$table->softDeletes();

			$table->primary('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('listings');
	}
}
