<?php namespace Poppy\Framework\Http\Graphql\Types;

use GraphQL\Type\Definition\Type;
use Poppy\Framework\GraphQL\Support\Type as AbstractType;

class PaginationType extends AbstractType
{

	public function __construct($attributes = [])
	{
		parent::__construct($attributes);
		$this->attributes['name']        = 'Pagination';
		$this->attributes['description'] = trans('poppy::http.types.desc');
	}

	/**
	 * @return array
	 */
	public function fields()
	{
		return [
			'page'  => [
				'type'        => Type::int(),
				'description' => trans('poppy::http.types.page'),
			],
			'size'  => [
				'type'        => Type::int(),
				'description' => trans('poppy::http.types.size'),
			],
			'total' => [
				'type'        => Type::int(),
				'description' => trans('poppy::http.types.total'),
			],
			'pages' => [
				'type'        => Type::int(),
				'description' => trans('poppy::http.types.pages'),
			],
		];
	}
}
