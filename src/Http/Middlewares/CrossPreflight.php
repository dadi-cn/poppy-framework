<?php namespace Poppy\Framework\Http\Middlewares;

use Closure;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

/**
 * Class CrossPreflight.
 */
class CrossPreflight
{
	/**
	 * @var \Illuminate\Contracts\Routing\ResponseFactory
	 */
	protected $response;

	/**
	 * EnableCrossRequest constructor.
	 * @param \Illuminate\Contracts\Routing\ResponseFactory $response
	 */
	public function __construct(ResponseFactory $response)
	{
		$this->response = $response;
	}

	/**
	 * Middleware handler.
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure                 $next
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		$headers = [
			'Access-Control-Allow-Origin'      => '*',
			'Access-Control-Allow-Headers'     => 'Origin,Content-Type,Cookie,Accept,Authorization,X-Requested-With',
			'Access-Control-Allow-Methods'     => 'DELETE,GET,POST,PATCH,PUT,OPTIONS',
			'Access-Control-Allow-Credentials' => 'true',
		];
		if ($request->getMethod() == 'OPTIONS') {
			return $this->response->make('OK', 200, $headers);
		}
		 
			return $next($request);
	}
}