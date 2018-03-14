<?php namespace Poppy\Framework\Console\Generators;

use Poppy\Framework\Console\GeneratorCommand;

class MakeSeederCommand extends GeneratorCommand
{
	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'poppy:seeder
    	{slug : The slug of the module.}
    	{name : The name of the seeder class.}';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Create a new module seeder class';

	/**
	 * String to store the command type.
	 * @var string
	 */
	protected $type = 'Module seeder';

	/**
	 * Get the stub file for the generator.
	 * @return string
	 */
	protected function getStub()
	{
		return __DIR__ . '/stubs/seeder.stub';
	}

	/**
	 * Get the destination class path.
	 * @param string $name
	 * @return string
	 * @throws \Poppy\Framework\Exceptions\ModuleNotFoundException
	 */
	protected function getPath($name)
	{
		return poppy_path($this->argument('slug'), 'src/database/seeds/' . $name . '.php');
	}

	/**
	 * Replace namespace in seeder stub.
	 * @param string $name
	 * @return string
	 * @throws \Poppy\Framework\Exceptions\ModuleNotFoundException
	 */
	protected function getNamespace($name)
	{
		return poppy_class($this->argument('slug'), 'Database\Seeds');
	}

	protected function rootNamespace()
	{
		return '';
	}
}
