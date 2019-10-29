<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableIndexesListings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('city');
            $table->index('state');
            $table->index('postcode');
            $table->index('latitude');
            $table->index('longitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropIndex('user_id');
            $table->dropIndex('city');
            $table->dropIndex('state');
            $table->dropIndex('postcode');
            $table->dropIndex('latitude');
            $table->dropIndex('longitude');
        });
    }
}
