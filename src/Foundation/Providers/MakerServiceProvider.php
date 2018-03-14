<?php namespace Poppy\Framework\Foundation\Providers;

use Illuminate\Support\ServiceProvider;
use Poppy\Framework\Foundation\Maker;

class MakerServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->singleton(Maker::class);
	}
}
