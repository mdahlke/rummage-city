<?php

namespace App\GraphQL\Types;

use App\SavedListing;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SavedListingsType extends GraphQLType {
	protected $attributes = [
		'name' => 'SavedListings',
		'description' => 'A user',
		'model' => SavedListing::class,
	];

	public function fields(): array {
		return [
			'listing_id' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'The id of the user',
			],
			'user_id' => [
				'type' => Type::string(),
				'description' => 'The name of the user',
			],
			'user' => [
				'type' => GraphQL::type('user'),
				'description' => 'The user who saved the listing',
				'always' => ['name'],
			],
			'listing' => [
				'type' => GraphQL::type('listings'),
				'description' => 'A list of listings saved by the user',
				//				'args' => [
				//					'end_date' => [
				//						'type' => Type::string(),
				//					],
				//				],
				'always' => ['title'],
			],
		];
	}
}
