<?php namespace Poppy\Framework\Application;

/**
 * Main Test Case
 */
class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
	/**
	 * Creates the application.
	 */
	public function createApplication()
	{
		$file         = __DIR__ . '/../../../storage/bootstrap/app.php';
		$fileInVendor = __DIR__ . '/../../../../../storage/bootstrap/app.php';
		if (file_exists($file)) {
			$app = require_once $file;
		}
		elseif (file_exists($fileInVendor)) {
			$app = require_once $fileInVendor;
		}

		if (isset($app)) {
			$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
			return $app;
		}
	}
}
