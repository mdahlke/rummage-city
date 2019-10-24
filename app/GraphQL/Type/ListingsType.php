<?php

namespace App\GraphQL\Types;

use App\Listing;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ListingsType extends GraphQLType {
	protected $attributes = [
		'name' => 'Listings',
		'description' => 'A listing',
		'model' => Listing::class,
	];

	public function fields(): array {
		return [
			'id' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'The id of the listing',
			],
			'title' => [
				'type' => Type::string(),
				'description' => 'The title of the listing',
			],
			'description' => [
				'type' => Type::string(),
				'description' => 'The description of the listing',
			],
			'address' => [
				'type' => Type::string(),
				'description' => 'The address where the listing is located',
			],
			'latitude' => [
				'type' => Type::float(),
				'description' => 'The latitude coordinates of the listing',
			],
			'longitude' => [
				'type' => Type::float(),
				'description' => 'The longitude coordinates of the listing',
			],
			'date' => [
				'type' => Type::listOf(GraphQL::type('listingDates')),
				'description' => 'A list of dates for the listing',
				'always' => ['start', 'end'],
			],
			'image' => [
				'type' => Type::listOf(GraphQL::type('listingImages')),
				'description' => 'A list of images for the listing',
				'always' => ['name', 'path'],
			],
			'user' => [
				'name' => 'user',
				'type' => GraphQL::type('user'),
				'always' => ['name'],
			],
		];
	}
}
