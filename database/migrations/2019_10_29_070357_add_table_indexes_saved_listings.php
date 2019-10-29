<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableIndexesSavedListings extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('saved_listings', function (Blueprint $table) {
            $table->index(['listing_id', 'user_id']);
            $table->index('listing_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('saved_listings', function (Blueprint $table) {
            $table->dropIndex(['listing_id', 'user_id']);
            $table->dropIndex('listing_id');
            $table->dropIndex('user_id');
        });
    }
}
