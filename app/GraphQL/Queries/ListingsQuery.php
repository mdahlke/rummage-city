<?php

namespace App\GraphQL\Query;

use App\Listing;
use Carbon\Carbon;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class ListingsQuery extends Query {
    protected $attributes = [
        'name' => 'Listings Query',
        'description' => 'A query of listings',
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
//        return Type::string();
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
            'limit' => [
                'name' => 'limit',
                'type' => Type::int(),
                'default' => 10,
            ],
            'page' => [
                'name' => 'page',
                'type' => Type::int(),
                'default' => 1
            ]
        ];
    }

    protected function rules(array $args = []): array {
        return [
            'limit' => ['int'],
            'page' => ['int']
        ];
    }

    public function validationErrorMessages(array $args = []): array {
        return [
            'limit.required' => 'The `limit` argument is required',
            'page.required' => 'The `page` argument is required',
            'limit.int' => 'The `limit` argument must be an `int`',
            'page.int' => 'The `page` argument must be an `int`',
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
            }
        };

        $select = $fields()->getSelect();

        $builder = Listing::query()
            ->select($select)
            // this allows ONLY still alive listings to appear.
            // @TODO ask Mark for the correct way of doing this
            ->selectRaw('(SELECT `start` FROM listing_dates WHERE listings.id = listing_dates.listing_id AND `end` >= "' . Carbon::now() . '"
	ORDER BY `start` LIMIT 1 ) as sort')
            ->with(array_keys($fields()->getRelations()))
            ->with(['activeDate' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->where($where)
            ->whereHas('activeDate')
            ->orderBy('sort');

        $page = ($args['page'] ?? 1);

        if (($args['limit'] ?? 0) > 0) {
            $limit = $args['limit'];
        } else {
            // set the limit high to get all listings
            $limit = (int)99999999;
            // set the page to zero so we get listings
            $page = 0;
        }

        return $builder->paginate($limit, ['*'], 'page', $page);
    }
}
