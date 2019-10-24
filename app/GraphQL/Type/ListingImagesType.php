<?php

namespace App\GraphQL\Types;

use App\ListingImage;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ListingImagesType extends GraphQLType {
	protected $attributes = [
		'name' => 'ListingImages',
		'description' => 'Images for a listing',
		'model' => ListingImage::class,
	];

	public function fields(): array {
		return [
			'id' => [
				'type' => Type::nonNull(Type::string()),
				'description' => 'The id of the listing image',
			],
			'name' => [
				'type' => Type::string(),
				'description' => 'The original filename of the image',
			],
			'path' => [
				'type' => Type::string(),
				'description' => 'The url of the image',
				'resolve' => function ($root, $args, $context) {
					return asset('/storage/' . $root['path']);
				},
			],
			'listing' => [
				'type' => GraphQL::type('listings'),
				'description' => 'The listing that the image belongs to',
				'always' => ['title'],
			],
		];
	}
}
