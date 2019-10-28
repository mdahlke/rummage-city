<?php

namespace App\GraphQL\Types;

use App\Listing;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ListingsSearchType extends GraphQLType
{
    protected $attributes = [
        'name' => 'ListingsSearch',
        'description' => 'Search listings and maintain search state',
        'model' => Listing::class,
    ];

    public function fields(): array
    {
        return [
            'listings' => [
                'type' => Type::listOf(GraphQL::type('listings')),
                'description' => 'States if the user has the listing saved or not',
            ],
            'searchState' => [
                'type' => Type::string(),
                'description' => 'JSON encoded string holding the search state',
//                'type' => Type::listOf();
            ]
        ];
    }
}
