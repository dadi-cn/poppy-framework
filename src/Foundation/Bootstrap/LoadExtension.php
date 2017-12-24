<?php namespace Poppy\Framework\Foundation\Bootstrap;

use Poppy\Framework\Classes\Traits\PoppyTrait;
use Poppy\Framework\Extension\Extension;
use Poppy\Framework\Foundation\Contracts\Bootstrap;

/**
 * Class LoadExtension.
 */
class LoadExtension implements Bootstrap
{
	use PoppyTrait;

	/**
	 * Bootstrap the given application.
	 */
	public function bootstrap()
	{
		$this->getExtension()->repository()->each(function (Extension $extension) {
			$providers = collect($this->getConfig()->get('app.providers'));
			if (isset($extension['service']) && $extension['service']) {
				$providers->push($extension->service());
				$this->getConfig()->set('app.providers', $providers->toArray());
			}
		});
	}
}
