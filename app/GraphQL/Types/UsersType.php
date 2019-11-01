<?php

namespace App\GraphQL\Types;

use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UsersType extends GraphQLType {
	protected $attributes = [
		'name' => 'User',
		'description' => 'A user',
		'model' => User::class,
	];

	public function fields(): array {
		return [
			'id' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'The id of the user',
			],
			'name' => [
				'type' => Type::string(),
				'description' => 'The name of the user',
			],
			'listing' => [
				'type' => Type::listOf(GraphQL::type('listings')),
				'description' => 'A list of listings created by the user',
				'always' => ['title']
			],
			'savedListing' => [
				'type' => Type::listOf(GraphQL::type('savedListings')),
				'description' => 'A list of listings saved by the user',
				'always' => ['listing_id']
			],
		];
	}
}
