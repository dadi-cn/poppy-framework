<?php namespace Poppy\Framework\GraphQL;

use Poppy\Framework\Support\ServiceProvider;

/**
 * Class GraphQLServiceProvider.
 */
class GraphQLServiceProvider extends ServiceProvider
{
	/**
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * @return array
	 */
	public function provides()
	{
		return ['graphql'];
	}

	/**
	 * Register Service Provider.
	 */
	public function register()
	{
		$this->app->singleton('graphql', function ($app) {
			$manager = new GraphQL($app);
			foreach ($app['config']['graphql']['types'] as $type) {
				$manager->addType($type);
			}
			foreach ($app['config']['graphql']['schemas'] as $name => $definition) {
				$manager->addSchema($name, $definition);
			}
			return $manager;
		});
	}
}
