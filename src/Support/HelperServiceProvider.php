<?php namespace Poppy\Framework\Support;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		require_once __DIR__ . '/functions.php';
	}
}
