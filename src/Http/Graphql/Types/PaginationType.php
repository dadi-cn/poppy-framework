<?php namespace Poppy\Framework\Http\Graphql\Types;

use GraphQL\Type\Definition\Type;
use Poppy\Framework\GraphQL\Support\Type as AbstractType;

class PaginationType extends AbstractType
{

	public function __construct($attributes = [])
	{
		parent::__construct($attributes);
		$this->attributes['name']        = 'Pagination';
		$this->attributes['description'] = trans('poppy::http.graphql.pagination_desc');
	}

	/**
	 * @return array
	 */
	public function fields()
	{
		return [
			'page'  => [
				'type'        => Type::int(),
				'description' => trans('poppy::http.graphql.pagination_page'),
			],
			'size'  => [
				'type'        => Type::int(),
				'description' => trans('poppy::http.graphql.pagination_size'),
			],
			'total' => [
				'type'        => Type::int(),
				'description' => trans('poppy::http.graphql.pagination_total'),
			],
			'pages' => [
				'type'        => Type::int(),
				'description' => trans('poppy::http.graphql.pagination_pages'),
			],
		];
	}
}
