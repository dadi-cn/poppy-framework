<?php namespace Poppy\Framework;

use Illuminate\Support\ServiceProvider;


class FrameworkServiceProvider extends ServiceProvider
{

	/**
	 * Indicates if loading of the provider is deferred.
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 * @return void
	 */
	public function boot()
	{
		// 注册 api 文档配置
		$this->publishes([
			__DIR__ . '/../config/poppy.php' => config_path('poppy.php'),
		], 'config');


		$this->app['poppy']->register();


		// 定义视图
		$this->loadViewsFrom(__DIR__ . '/../resources/views', 'poppy');
		$this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'poppy');
	}

	/**
	 * Register the service provider.
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__ . '/../config/poppy.php', 'poppy'
		);

	}

	/**
	 * Get the services provided by the provider.
	 * @return array
	 */
	public function provides()
	{
		// return ['duoli.form'];
		return [];
	}

	/**
	 * @return array
	 * @throws Exceptions\ModuleNotFoundException
	 */
	protected function providerFiles()
	{
		$modules = app()->make('poppy')->all();
		$files   = [];

		foreach ($modules as $module) {
			$serviceProvider = poppy_class($module['slug'], 'ServiceProvider');
			if (class_exists($serviceProvider)) {
				$files[] = $serviceProvider;
			}
		}

		return $files;
	}

	/*
	public static function compiles()
	{
		$modules = app()->make('poppy')->all();
		$files   = [];

		foreach ($modules as $module) {
			$serviceProvider = poppy_module_class($module['slug'], 'Providers\\ModuleServiceProvider');
			var_dump($serviceProvider);
			if (class_exists($serviceProvider)) {
				$files = array_merge($files, forward_static_call([$serviceProvider, 'compiles']));
			}
		}

		return array_map('realpath', $files);
	}
	*/

}
