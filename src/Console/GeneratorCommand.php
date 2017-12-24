<?php namespace Poppy\Framework\Console;

use Illuminate\Console\GeneratorCommand as LaravelGeneratorCommand;
use Illuminate\Support\Str;

abstract class GeneratorCommand extends LaravelGeneratorCommand
{
	/**
	 * Parse the name and format according to the root namespace.
	 * @param string $name
	 * @return string
	 */
	protected function parseName($name)
	{
		$rootNamespace = '';
		if (Str::startsWith($name, $rootNamespace)) {
			return $name;
		}

		if (Str::contains($name, '/')) {
			$name = str_replace('/', '\\', $name);
		}

		return $this->parseName($this->getDefaultNamespace(trim($rootNamespace, '\\')) . '\\' . $name);
	}

	/**
	 * Parse the class name and format according to the root namespace.
	 * @param  string $name
	 * @return string
	 */
	protected function qualifyClass($name)
	{
		$name = studly_case(ltrim($name, '\\/'));

		$rootNamespace = $this->rootNamespace();

		if (Str::startsWith($name, $rootNamespace)) {
			return $name;
		}

		$name = str_replace('/', '\\', $name);

		return $this->getDefaultNamespace(trim($rootNamespace, '\\')) ?
			$this->getDefaultNamespace(trim($rootNamespace, '\\')) . '\\' . $name
			: $name;
	}

	/**
	 * Get the destination class path.
	 * @param string $name
	 * @return string
	 * @throws \Poppy\Framework\Exceptions\ModuleNotFoundException
	 */
	protected function getPath($name)
	{

		$slug = $this->argument('slug');

		// take everything after the module name in the given path (ignoring case)
		$key = array_search(strtolower($slug), explode('\\', strtolower($name)));
		if ($key === false) {
			$newPath = str_replace('\\', '/', $name);
		}
		else {
			$newPath = implode('/', array_slice(explode('\\', $name), $key + 1));
		}

		$newPath = "{$newPath}.php";

		$pathInfo = pathinfo($newPath);

		if (strpos($pathInfo['dirname'], 'Tests') !== false) {
			$addSrc = '';
		}
		else {
			$addSrc = 'src' . DIRECTORY_SEPARATOR;
		}

		$path = poppy_path(
			$slug,
			$addSrc .
			strtolower($pathInfo['dirname']) . DIRECTORY_SEPARATOR .
			"{$pathInfo['basename']}"
		);

		return $path;
	}
}
