<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
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
    protected $keyType = 'string';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public $incrementing = false;
    public $savedListingsIds = [];

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

    public function listing(): HasMany {
        return $this->hasMany(Listing::class, 'user_id', 'id');
    }

    public function savedListing(): HasMany {
        return $this->hasMany(SavedListing::class);
    }

    public static function storagePath(): string {
        return 'uploads/' . Auth::user()->id;
    }

    public function fetchSavedListings(): array {
        $saved = $this->savedListing;

        return $this->savedListingsIds = $saved->pluck('listing_id')->all();
    }

    public function hasSavedListing(string $id): bool {
        if (!$this->savedListingsIds) {
            $this->fetchSavedListings();
        }

        return in_array($id, $this->savedListingsIds);
    }
}
