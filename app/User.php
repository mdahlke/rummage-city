<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
	public $incrementing = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'uuid',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *L
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	public function listing() {
		return $this->hasMany(Listing::class, 'user_id', 'id');
	}

	public function savedListing() {
		return $this->hasMany(SavedListing::class);
	}
}
