<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Webpatser\Uuid\Uuid;

/**
 * Class Listing
 * @package App
 *
 * @property string user_id
 * @property string listing_id
 * @property string created_at
 * @property string updated_at
 */
class SavedListing extends Model {
	use Uuids;

	public $fillable = ['id', 'listing_id'];

	public function listing() {
		return $this->hasOne(Listing::class, 'id', 'listing_id');
	}

	public function user() {
		return $this->hasOne(User::class, 'id', 'user_id');
	}

}
