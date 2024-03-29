<?php

namespace App\GraphQL\Query;

use App\User;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class UsersQuery extends Query {
	protected $attributes = [
		'name' => 'Users Query',
		'description' => 'A query of users',
	];

	/**
	 * @return Type
	 */
	public function type(): Type {
		// result of query with pagination laravel
		return GraphQL::paginate('user');
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
		};

		$user = User::with($fields()->getRelations())
		            ->where($where)
		            ->select($fields()->getSelect())
		            ->paginate();
		return $user;
	}
}
