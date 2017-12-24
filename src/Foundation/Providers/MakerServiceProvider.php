<?php namespace Poppy\Framework\Foundation\Providers;


use Poppy\Framework\Foundation\Maker;
use Illuminate\Support\ServiceProvider;

class MakerServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->singleton(Maker::class);
	}
}
