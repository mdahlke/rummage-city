<?php
/**
 * File: Uuids.php
 * Date: 10/16/19
 * Time: 2:22 PM
 *
 * @package rummagecity.dev
 * @author Michael Dahlke <mdahlke@wisnet.com>
 */

namespace App;

use Webpatser\Uuid\Uuid;

trait Uuids {

	/**
	 * Boot function from laravel.
	 */
	protected static function boot() {
		parent::boot();

		static::creating(function ($model) {
			if (empty($model->{$model->getKeyName()})) {
				$model->{$model->getKeyName()} = Uuid::generate()->string;
			}
		});
	}
}
