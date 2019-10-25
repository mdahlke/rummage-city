<?php

namespace App;

use Carbon\Traits\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class ListingImage
 * @package App
 *
 * @property string id
 * @property string listing_id
 * @property string path
 * @property string name
 * @property Date updated_at
 * @property Date created_at
 */
class ListingImage extends Model {
	use Uuids;
	use Notifiable;

	const STORAGE_PATH = 'uploads/listings';

	protected $keyType = 'string';
	protected $dates = ['created_at', 'updated_at'];

	public $incrementing = false;
	protected $appends = [
		'url',
	];
	public $fillable = ['path', 'name'];
	public $url = '';

	public function listing() {
		return $this->belongsTo(Listing::class);
	}

	public function getUrlAttribute() {
		return $this->url;
	}
}
