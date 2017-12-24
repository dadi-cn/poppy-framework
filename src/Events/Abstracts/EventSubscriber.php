<?php namespace Poppy\Framework\Events\Abstracts;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Str;
use Poppy\Framework\Foundation\Application;

/**
 * Class EventSubscriber.
 */
abstract class EventSubscriber
{
	/**
	 * @var \Illuminate\Container\Container|Application
	 */
	protected $container;

	/**
	 * @var \Illuminate\Events\Dispatcher
	 */
	protected $events;

	/**
	 * EventSubscriber constructor.
	 * @param \Illuminate\Container\Container $container
	 * @param \Illuminate\Events\Dispatcher   $events
	 */
	public function __construct(Container $container, Dispatcher $events)
	{
		$this->container = $container;
		$this->events    = $events;
	}

	/**
	 * Name of event.
	 * @throws \Exception
	 * @return string|object
	 */
	abstract protected function getEvent();

	/**
	 * Event subscribe handler.
	 * @throws \Exception
	 */
	public function subscribe()
	{
		$method = 'handle';
		if (method_exists($this, $getHandler = 'get' . Str::ucfirst($method) . 'r')) {
			$method = $this->{$getHandler}();
		}
		$this->events->listen($this->getEvent(), [
			$this,
			$method,
		]);
	}
}
