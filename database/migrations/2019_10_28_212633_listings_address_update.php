<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ListingsAddressUpdate extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('listings', function (Blueprint $table) {
            $table->string('country_code')->after('description');
            $table->string('postcode')->after('description');
            $table->string('state')->after('description');
            $table->string('city')->after('description');
            $table->string('street_name')->after('description');
            $table->string('house_number')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('listings', function (Blueprint $table) {
            $table->removeColumn('house_number');
            $table->removeColumn('street_name');
            $table->removeColumn('city');
            $table->removeColumn('state');
            $table->removeColumn('postcode');
            $table->removeColumn('country_code');
        });
    }
}
