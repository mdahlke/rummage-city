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
    protected $keyType = 'string';
    public $incrementing = false;

    public $fillable = ['user_id', 'listing_id'];

    public function listing() {
        return $this->belongsToMany(Listing::class, 'id', 'listing_id');
//            ->with('listing');
    }

    public function user() {
        return $this->belongsToMany(User::class, 'id', 'user_id');
    }

}
