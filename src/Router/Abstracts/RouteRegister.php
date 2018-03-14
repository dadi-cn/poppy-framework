<?php namespace Poppy\Framework\Router\Abstracts;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Router;
use Poppy\Framework\Events\Abstracts\EventSubscriber;
use Poppy\Framework\Router\Events\RouteRegister as RouteRegisterEvent;

/**
 * Class AbstractRouteRegister.
 */
abstract class RouteRegister extends EventSubscriber
{
	protected $router;

	/**
	 * RouteRegister constructor.
	 * @param Container  $container
	 * @param Dispatcher $events
	 * @param Router     $router
	 */
	public function __construct(Container $container, Dispatcher $events, Router $router)
	{
		parent::__construct($container, $events);
		$this->router = $router;
	}

	/**
	 * Name of event.
	 * @return mixed
	 */
	protected function getEvent()
	{
		return RouteRegisterEvent::class;
	}

	/**
	 * Handle Route Register.
	 */
	abstract public function handle();
}
