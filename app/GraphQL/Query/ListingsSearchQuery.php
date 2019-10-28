<?php

namespace App\GraphQL\Query;

use App\Listing;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class ListingsSearchQuery extends Query {
    protected $attributes = [
        'name' => 'ListingsSearchQuery',
        'description' => 'A query of listings with search state',
    ];

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool {
        // true, if logged in

        //		return !Auth::guest();
        return true;
    }

    /**
     * @return Type
     */
    public function type(): Type {
        // result of query with pagination laravel
        return GraphQL::type('listingsSearch');
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
                $sw = $bounds->sw;
                $ne = $bounds->ne;

                $query->where(function ($q) use ($sw, $ne) {
                    $q->whereBetween('latitude', [$sw->lat, $ne->lat])
                        ->whereBetween('longitude', [$sw->lng, $ne->lng]);
                });

//                $query->where('latitude', '>', $sw->lat)
//                    ->where('latitude', '<', $ne->lat)
//                    ->where('longitude', '>', $sw->lng)
//                    ->where('longitude', '<', $ne->lng);
            }
        };
        $listings = Listing::query()
            ->with(array_keys($fields()->getRelations()))
            ->where($where)
            ->whereHas('activeDate')
            ->select($fields()->getSelect())
            ->paginate(10000);

        return $listings;
    }
}
