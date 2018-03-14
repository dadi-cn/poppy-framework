<?php namespace Poppy\Framework\Foundation\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	protected $bootstrappers = [
		'Poppy\Framework\Foundation\Bootstrap\RegisterClassLoader',   // poppy module loader
		'Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables',
		'Illuminate\Foundation\Bootstrap\LoadConfiguration',
		'Illuminate\Foundation\Bootstrap\HandleExceptions',
		'Illuminate\Foundation\Bootstrap\RegisterFacades',
		'Illuminate\Foundation\Bootstrap\SetRequestForConsole',
		'Illuminate\Foundation\Bootstrap\RegisterProviders',
		'Illuminate\Foundation\Bootstrap\BootProviders',
	];

	/**
	 * 定义计划命令
	 * @param  Schedule $schedule
	 */
	protected function schedule(Schedule $schedule)
	{
		$this->app['events']->fire('console.schedule', [$schedule]);
	}
}