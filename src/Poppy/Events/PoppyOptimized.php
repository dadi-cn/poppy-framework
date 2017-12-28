<?php namespace Poppy\Framework\Poppy\Events;

use Illuminate\Support\Collection;

class PoppyOptimized
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