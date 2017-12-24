<?php namespace Poppy\Framework\Application;

class TestCase extends \Illuminate\Foundation\Testing\TestCase
{

	/**
	 * Creates the application.
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{

		$app = require __DIR__ . '/../../../storage/bootstrap/app.php';

		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		return $app;
	}

}
