<?php namespace Poppy\Framework\Parse;

use Illuminate\Support\ServiceProvider;

class ParseServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the service provider.
	 * @return void
	 */
	public function register()
	{

		$this->app->singleton('poppy.parse.yaml', function ($app) {
			return new Yaml;
		});

		$this->app->singleton('poppy.parse.ini', function ($app) {
			return new Ini;
		});

		$this->app->singleton('poppy.parse.xml', function ($app) {
			return new Xml;
		});
	}

	/**
	 * Get the services provided by the provider.
	 * @return array
	 */
	public function provides()
	{
		return [
			'poppy.parse.yaml',
			'poppy.parse.ini',
			'poppy.parse.xml',
		];
	}
}
