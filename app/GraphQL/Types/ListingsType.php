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
            'house_number' => [
                'type' => Type::string(),
                'description' => 'The house number where the listing is located',
            ],
            'street_name' => [
                'type' => Type::string(),
                'description' => 'The name of the street where the listing is located',
            ],
            'city' => [
                'type' => Type::string(),
                'description' => 'The city where the listing is located',
            ],
            'state' => [
                'type' => Type::string(),
                'description' => 'The state where the listing is located',
            ],
            'postcode' => [
                'type' => Type::string(),
                'description' => 'The postcode where the listing is located',
            ],
            'country_code' => [
                'type' => Type::string(),
                'description' => 'The country code where the listing is located (IE: US)',
            ],
            'latitude' => [
                'type' => Type::float(),
                'description' => 'The latitude coordinates of the listing',
            ],
            'longitude' => [
                'type' => Type::float(),
                'description' => 'The longitude coordinates of the listing',
            ],
            'active_date' => [
                'name' => 'active_date',
                'type' => Type::listOf(GraphQL::type('listingDates')),
                'description' => 'A list of active dates for the listing',
                'always' => ['start', 'end'],
                'alias' => 'activeDate',
            ],
            'date' => [
                'name' => 'date',
                'type' => Type::listOf(GraphQL::type('listingDates')),
                'description' => 'A list of all (including past) dates for the listing',
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
                'description' => 'The user who created the listing',
                'always' => ['name'],
            ],
            'saveUrl' => [
                'type' => Type::string(),
                'description' => 'The url to visit to add the listing to a user\'s saved list',
                'selectable' => false, // Does not try to query this from the database
            ],
            'removeSavedUrl' => [
                'type' => Type::string(),
                'description' => 'The url to visit to remove the listing from a user\'s saved list',
                'selectable' => false, // Does not try to query this from the database
            ],
            'isSaved' => [
                'type' => Type::string(),
                'description' => 'States if the user has the listing saved or not',
                'selectable' => false, // Does not try to query this from the database
            ]
        ];
    }
}
