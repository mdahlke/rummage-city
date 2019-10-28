<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Webpatser\Uuid\Uuid;

/**
 * Class Listing
 * @package App
 *
 * @property string id
 * @property string listing_id
 * @property string start
 * @property string end
 * @property string created_at
 * @property string updated_at
 */
class ListingDate extends Model {
    use Uuids;

    protected $dates = ['created_at', 'updated_at', 'start', 'end'];
    public $fillable = ['start', 'end'];

    public function listing() {
        return $this->belongsTo(Listing::class)
            ->where('end', '>=', Carbon::today())
            ->orderByAsc('start');
    }

    public function activeDate() {
        return $this->belongsTo(Listing::class, 'listing_id', 'id')->where('end', '>=', Carbon::today());
    }
}
