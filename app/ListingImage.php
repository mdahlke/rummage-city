<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ListingImage
 * @package App
 *
 * @property string id
 * @property string listing_id
 * @property string path
 * @property string name
 * @property string updated_at
 * @property string created_at
 */
class ListingImage extends Model {
	use Uuids;
	protected $keyType = 'string';
	public $incrementing = false;
	const STORAGE_PATH = 'uploads/listings';

	public $fillable = ['path', 'name'];

	public function listing() {
		return $this->belongsTo(Listing::class);
	}
}
