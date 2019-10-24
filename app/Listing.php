<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Listing
 * @package App
 *
 * @property string id
 * @property string user_id
 * @property string title
 * @property string description
 * @property string address
 * @property float latitude
 * @property float longitude
 * @property string ip_address
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 */
class Listing extends Model {
	use Uuids;

	protected $keyType = 'string';
	public $incrementing = false;

	public $fillable = ['title', 'description', 'address', 'latitude', 'longitude', 'ip_address'];

	public function addressEncoded() {
		return urlencode($this->address);
	}

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function date() {
		return $this->hasMany(ListingDate::class);
	}

	public function image() {
		return $this->hasMany(ListingImage::class);
	}

	public function hasActiveDate() {
		$hasActive = false;
		$now = new Carbon();

		foreach ($this->date as $date) {
			try {
				$d = new Carbon($date->end);

				if ($d->gt($now)) {
					$hasActive = true;
					break;
				}
			}
			catch (\Exception $e) {
				//
			}
		}

		return $hasActive;
	}
}
