<?php

namespace App\GraphQL\Query;

use App\ListingImage;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ListingImagesQuery extends Query {
	protected $attributes = [
		'name' => 'Listing Images Query',
		'description' => 'A query of images for a listing',
	];

	/**
	 * @return Type
	 */
	public function type(): Type {
		// result of query with pagination laravel
		return GraphQL::paginate('listingImages');
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
			'listing_id' => [
				'name' => 'listing_id',
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
			if (isset($args['id'])) {
				$query->where('id', $args['id']);
			}
			if (isset($args['listing_id'])) {
				$query->where('listing_id', $args['listing_id']);
			}
		};

		$user = ListingImage::with($fields()->getRelations())
		            ->where($where)
		            ->select($fields()->getSelect())
		            ->paginate();
		return $user;
	}
}
