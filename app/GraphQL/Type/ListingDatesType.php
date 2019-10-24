<?php

namespace App\GraphQL\Types;

use App\ListingDate;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ListingDatesType extends GraphQLType {
	protected $attributes = [
		'name' => 'ListingDates',
		'description' => 'The date(s) for a listing',
		'model' => ListingDate::class,
	];

	public function fields(): array {
		return [
			'start' => [
				'type' => Type::string(),
				'description' => 'The starting date/time of the listing',
			],
			'end' => [
				'type' => Type::string(),
				'description' => 'The ending date/time of the listing',
			],
			'listing' => [
				'name' => 'listing',
				'type' => GraphQL::type('listings'),
				'description' => 'A list of dates for the listing',
				'always' => ['start', 'end'],
			],
		];
	}
}
