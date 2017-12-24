<?php namespace Poppy\Framework;

use Illuminate\Support\ServiceProvider;
use Poppy\Framework\Console\ConsoleServiceProvider;
use Poppy\Framework\Console\GeneratorServiceProvider;
use Poppy\Framework\GraphQL\GraphQLServiceProvider;
use Poppy\Framework\Parse\ParseServiceProvider;
use Poppy\Framework\Poppy\PoppyServiceProvider;
use Poppy\Framework\Providers\BladeServiceProvider;
use Poppy\Framework\Support\HelperServiceProvider;
use Poppy\Framework\Translation\TranslationServiceProvider;


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

		$this->app->register(ConsoleServiceProvider::class);
		$this->app->register(GeneratorServiceProvider::class);
		$this->app->register(BladeServiceProvider::class);
		$this->app->register(HelperServiceProvider::class);
		$this->app->register(PoppyServiceProvider::class);
		$this->app->register(ParseServiceProvider::class);
		$this->app->register(TranslationServiceProvider::class);
		$this->app->register(GraphQLServiceProvider::class);
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
