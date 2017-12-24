<?php namespace Poppy\Framework\Console;

use Illuminate\Support\ServiceProvider;

class GeneratorServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 */
	public function register()
	{
		$generators = [
			'command.make.poppy'            => \Poppy\Framework\Console\Generators\MakePoppyCommand::class,
			'command.make.poppy.controller' => \Poppy\Framework\Console\Generators\MakeControllerCommand::class,
			'command.make.poppy.middleware' => \Poppy\Framework\Console\Generators\MakeMiddlewareCommand::class,
			'command.make.poppy.migration'  => \Poppy\Framework\Console\Generators\MakeMigrationCommand::class,
			'command.make.poppy.model'      => \Poppy\Framework\Console\Generators\MakeModelCommand::class,
			'command.make.poppy.policy'     => \Poppy\Framework\Console\Generators\MakePolicyCommand::class,
			'command.make.poppy.provider'   => \Poppy\Framework\Console\Generators\MakeProviderCommand::class,
			'command.make.poppy.request'    => \Poppy\Framework\Console\Generators\MakeRequestCommand::class,
			'command.make.poppy.seeder'     => \Poppy\Framework\Console\Generators\MakeSeederCommand::class,
			'command.make.poppy.test'       => \Poppy\Framework\Console\Generators\MakeTestCommand::class,
			'command.make.poppy.command'    => \Poppy\Framework\Console\Generators\MakeCommandCommand::class,
		];

		foreach ($generators as $slug => $class) {
			$this->app->singleton($slug, function ($app) use ($slug, $class) {
				return $app[$class];
			});

			$this->commands($slug);
		}
	}
}
