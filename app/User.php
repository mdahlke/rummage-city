<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use mysql_xdevapi\Collection;

/**
 * Class User
 * @package App
 *
 * @property string id
 * @property string name
 * @property string email
 * @property string password
 * @property string remember_token
 * @property string created_at
 * @property string updated_at
 */
class User extends Authenticatable {
    use Uuids;
    use Notifiable;
    use HasApiTokens;
    protected $keyType = 'string';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $appends = ['gravatar'];

    public $incrementing = false;
    public $savedListingsIds = [];
    public $gravatar = '';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *L
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function __toString() {
        return $this->name;
    }
    public function getGravatarAttribute() {
        return $this->gravatar;
    }

    public function listing(): HasMany {
        return $this->hasMany(Listing::class, 'user_id', 'id');
    }

    public function activeListing(): HasMany {
        return $this->listing()
            ->whereHas('activeDate');
    }

    public function inactiveListings(): HasMany {
        return $this->listing()
            ->whereDoesntHave('activeDate');
    }

    public function savedListing(): BelongsToMany {
        return $this->belongsToMany(Listing::class, 'saved_listings')
            ->whereHas('activeDate')
            ->withTimestamps();
    }

    public static function storagePath(): string {
        return 'uploads/' . Auth::user()->id;
    }

    public function fetchSavedListings(): array {
        return $this->savedListingsIds = $this->savedListing()->pluck('listing_id')->all();
    }

    public function hasSavedListing(string $id): bool {
        if (!$this->savedListingsIds) {
            $this->fetchSavedListings();
        }

        return in_array($id, $this->savedListingsIds);
    }

    public function routeNotificationForSlack() {
        return "https://hooks.slack.com/services/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";
    }
}
