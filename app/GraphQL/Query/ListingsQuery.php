<?php

namespace App\GraphQL\Query;

use App\Listing;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class ListingsQuery extends Query {
	protected $attributes = [
		'name' => 'Listings Query',
		'description' => 'A query of listings',
	];

	/**
	 * @return Type
	 */
	public function type(): Type {
		// result of query with pagination laravel
		return GraphQL::paginate('listings');
	}

	/**
	 * @return array
	 */
	public function args(): array {
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::string(),
			],
			'bounds' => [
				'name' => 'bounds',
				'type' => Type::string(),
			],
		];
	}

	/**
	 * @param $root
	 * @param $args
	 * @param $context
	 * @param ResolveInfo $resolveInfo
	 * @param Closure $fields
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $fields) {
		$where = function ($query) use ($args) {
			/** @var  Builder $query */

			if (isset($args['id'])) {
				$query->where('id', $args['id']);
			}
			if (isset($args['bounds'])) {
				$bounds = json_decode($args['bounds']);
				$se = $bounds->se;
				$nw = $bounds->nw;

//				$query->where(function ($q) use () {
//					$q->whereBetween('latitude', [$se->lat, $nw->lat])
//					  ->whereBetween('longitude', [$se->lng, $nw->lng]);
//				})

								$query->where('latitude', '>', $se->lat)
								      ->where('latitude', '<', $nw->lat)
								      ->where('longitude', '<', $se->lng)
								      ->where('longitude', '>', $nw->lng);
			}
		};
		$user = Listing::with(array_keys($fields()->getRelations()))
		               ->where($where)
		               ->select($fields()->getSelect())
		               ->paginate();
		return $user;
	}
}
