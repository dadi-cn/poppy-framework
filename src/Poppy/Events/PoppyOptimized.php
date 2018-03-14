<?php namespace Poppy\Framework\Poppy\Events;

use Illuminate\Support\Collection;
use Poppy\Framework\Application\Event;

class PoppyOptimized extends Event
{
	/**
	 * Optimized module collection
	 * @var Collection
	 */
	private $modules;

	public function __construct($modules)
	{
		$this->modules = $modules;
	}

	public function modules()
	{
		return $this->modules;
	}
}