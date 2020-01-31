<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
 * @property string house_number
 * @property string street_name
 * @property string city
 * @property string state
 * @property string postcode
 * @property string country_code
 * @property float latitude
 * @property float longitude
 * @property string ip_address
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 * @property bool active
 */
class Listing extends Model {
    use Uuids;

    protected $keyType = 'string';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $appends = ['viewUrl', 'saveUrl', 'removeSavedUrl', 'isSaved'];
    public $incrementing = false;
    public $fillable = [
        'title',
        'description',
        'address',
        'house_number',
        'street_name',
        'city',
        'state',
        'postcode',
        'country_code',
        'latitude',
        'longitude',
        'ip_address'
    ];
    public $viewUrl = '';
    public $saveUrl = '';
    public $removeSavedUrl = '';
    public $isSaved = false;

    public function addressEncoded() {
        return urlencode($this->address);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function savedListing(): BelongsToMany {
        return $this->belongsToMany(User::class, 'saved_listings')
            ->whereHas('activeDate')
            ->withTimestamps();
    }

    public function date() {
        return $this->hasMany(ListingDate::class)->orderBy('start');
    }

    public function activeDate() {
        return $this->hasMany(ListingDate::class)
            ->whereHas('activeDate')
            ->orderBy('start');
    }

    public function image() {
        return $this->hasMany(ListingImage::class);
    }

    /*
     * Add $viewUrl to attributes list
     */
    public function getViewUrlAttribute() {
        return $this->viewUrl;
    }

    /*
     * Add $saveUrl to attributes list
     */
    public function getSaveUrlAttribute() {
        return $this->saveUrl;
    }

    /*
     * Add $removeSavedUrl to attributes list
     */
    public function getRemoveSavedUrlAttribute() {
        return $this->removeSavedUrl;
    }

    /*
     * Add $isSaved to attributes list
     */
    public function getIsSavedAttribute() {
        return $this->isSaved();
    }

    /**
     * @return string
     */
    public function getViewUrl(): string {
        return $this->viewUrl;
    }

    /**
     * @param string $viewUrl
     * @return Listing
     */
    public function setViewUrl(string $viewUrl): Listing {
        $this->viewUrl = $viewUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getSaveUrl(): string {
        return $this->saveUrl;
    }

    /**
     * @param string $saveUrl
     * @return Listing
     */
    public function setSaveUrl(string $saveUrl): Listing {
        $this->saveUrl = $saveUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getRemoveSavedUrl(): string {
        return $this->removeSavedUrl;
    }

    /**
     * @param string $removeSavedUrl
     * @return Listing
     */
    public function setRemoveSavedUrl(string $removeSavedUrl): Listing {
        $this->removeSavedUrl = $removeSavedUrl;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSaved(): bool {
        return (bool)$this->isSaved;
    }

    /**
     * @param bool $isSaved
     * @return Listing
     */
    public function setIsSaved(bool $isSaved): Listing {
        $this->isSaved = $isSaved;
        return $this;
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
            } catch (\Exception $e) {
                //
            }
        }

        return $hasActive;
    }
}
