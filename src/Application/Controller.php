<?php namespace Poppy\Framework\Application;

use Poppy\Framework\Agamotto\Agamotto;
use Poppy\Framework\Helper\EnvHelper;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{

	use DispatchesJobs, ValidatesRequests;

	/**
	 * @var int
	 */
	protected $pagesize = 15;

	/**
	 * @var string
	 */
	protected $ip;

	/**
	 * @var Agamotto
	 */
	protected $now;

	/**
	 * @var string
	 */
	protected $route;

	public function __construct()
	{
		$this->route = \Route::currentRouteName();
		\View::share([
			'_route' => $this->route,
		]);

		// pagesize
		$this->pagesize = config('poppy.pagesize', 15);
		if (\Input::get('pagesize')) {
			$pagesize = abs(intval(\Input::get('pagesize')));
			$pagesize = $pagesize < 3001 ? $pagesize : 3000;
			if ($pagesize > 0) {
				$this->pagesize = $pagesize;
			}
		}

		$this->ip  = EnvHelper::ip();
		$this->now = Agamotto::now();

		\View::share([
			'_ip'       => $this->ip,
			'_now'      => $this->now,
			'_pagesize' => $this->pagesize,
		]);
	}

}

