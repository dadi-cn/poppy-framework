<?php


if (!function_exists('route_url')) {
	/**
	 * 自定义可以传值的路由写法
	 * @param       $route
	 * @param array $route_params
	 * @param array $params
	 * @return string
	 */
	function route_url($route = '', $route_params = [], $params = null)
	{
		if (is_null($route_params)) {
			$route_params = [];
		}
		if ($route == '') {
			$route     = \Route::currentRouteName();
			$route_url = route($route, $route_params);
		}
		else {
			// 没有 . , 被认为是url
			if (strpos($route, '.') === false) {
				$route_url = url($route, $route_params);
			}
			else {
				$route_url = route($route, $route_params);
			}
		}
		return $route_url . (!empty($params) ? '?' . (is_array($params) ? http_build_query($params) : $params) : '');
	}
}


if (!function_exists('route_current')) {
	/**
	 * 当前路由的样式
	 * @param string|array $route
	 * @param string       $class      符合的当前类的名称
	 * @param string       $else_class 不符合当前类的样式
	 * @return string
	 */
	function route_current($route, $class = 'current', $else_class = ' ')
	{
		if (in_array(\Route::currentRouteName(), (array) $route)) {
			return $class;
		}
		else {
			return $else_class;
		}
	}
}

if (!function_exists('route_prefix')) {
	/**
	 * 路由前缀
	 */
	function route_prefix()
	{
		$route = \Route::currentRouteName();
		if (!$route) {
			return '';
		}
		else {
			return substr($route, 0, strpos($route, ':'));
		}
	}
}

if (!function_exists('command_exist')) {
	/**
	 * 检测命令是否存在
	 * @param $cmd
	 * @return bool
	 */
	function command_exist($cmd)
	{
		$returnVal = shell_exec("which $cmd");
		return (empty($returnVal) ? false : true);
	}
}


if (!function_exists('cache_name')) {
	/**
	 * 缓存前缀生成
	 * @param string $class
	 * @param string $suffix
	 * @return string
	 */
	function cache_name($class, $suffix = '')
	{
		return \Poppy\Framework\Helper\CacheHelper::name($class, $suffix);
	}
}

if (!function_exists('kv')) {
	/**
	 * 返回定义的kv 值
	 * 一般用户模型中的数据返回
	 * @param array $desc
	 * @param null  $key
	 * @param bool  $check_key 检查key 是否正常
	 * @return array|string
	 */
	function kv($desc, $key = null, $check_key = false)
	{
		if ($check_key) {
			return isset($desc[$key]) ? true : false;
		}
		else {
			return !is_null($key)
				? isset($desc[$key]) ? $desc[$key] : ''
				: $desc;
		}
	}
}


if (!function_exists('input')) {
	/**
	 * Returns an input parameter or the default value.
	 * Supports HTML Array names.
	 * <pre>
	 * $value = input('value', 'not found');
	 * $name = input('contact[name]');
	 * $name = input('contact[location][city]');
	 * </pre>
	 * Booleans are converted from strings
	 * @param string $name
	 * @param string $default
	 * @return string|array
	 */
	function input($name = null, $default = null)
	{
		if ($name === null)
			return Input::all();

		/*
		 * Array field name, eg: field[key][key2][key3]
		 */
		$name = implode('.', \Sour\Lemon\Html\RequestHelper::nameToArray($name));

		return Input::get($name, $default);
	}
}


if (!function_exists('is_post')) {
	/**
	 * 当前访问方法是否是post请求
	 * @return bool
	 */
	function is_post()
	{
		return \Input::method() == 'POST';
	}
}

if (!function_exists('post')) {
	/**
	 * Identical function to input(), however restricted to $_POST values.
	 * @param null $name
	 * @param null $default
	 * @return mixed
	 */
	function post($name = null, $default = null)
	{
		if ($name === null)
			return $_POST;

		/*
		 * Array field name, eg: field[key][key2][key3]
		 */
		$name = implode('.', \Sour\Lemon\Html\RequestHelper::nameToArray($name));


		return array_get($_POST, $name, $default);
	}
}


if (!function_exists('get')) {
	/**
	 * Identical function to input(), however restricted to $_GET values.
	 * @param null $name
	 * @param null $default
	 * @return mixed
	 */
	function get($name = null, $default = null)
	{
		if ($name === null)
			return $_GET;

		/*
		 * Array field name, eg: field[key][key2][key3]
		 */
		$name = implode('.', \Sour\Lemon\Html\RequestHelper::nameToArray($name));

		return array_get($_GET, $name, $default);
	}
}

if (!function_exists('plugins_path')) {
	/**
	 * Get the path to the plugins folder.
	 * @param  string $path
	 * @return string
	 */
	function plugins_path($path = '')
	{
		return app('path.plugins') . ($path ? '/' . $path : $path);
	}
}


if (!function_exists('poppy_path')) {

	/**
	 * Return the path to the given module file.
	 * @param string $slug
	 * @param string $file
	 * @return string
	 * @throws \Poppy\Framework\Exceptions\ModuleNotFoundException
	 */
	function poppy_path($slug = null, $file = '')
	{
		$modulesPath = app('path.module');

		$filePath = $file ? '/' . ltrim($file, '/') : '';

		if (is_null($slug)) {
			if (empty($file)) {
				return $modulesPath;
			}

			return $modulesPath . $filePath;
		}

		$module = app('poppy')->where('slug', $slug);

		if (is_null($module)) {
			throw new \Poppy\Framework\Exceptions\ModuleNotFoundException($slug);
		}

		return $modulesPath . '/' . $module['slug'] . $filePath;
	}
}

if (!function_exists('poppy_class')) {

	/**
	 * Return the full path to the given module class or namespace.
	 * @param string $slug
	 * @param string $class
	 * @return string
	 * @throws \Poppy\Framework\Exceptions\ModuleNotFoundException
	 */
	function poppy_class($slug, $class = '')
	{
		$module = app('poppy')->where('slug', $slug);

		if (is_null($module) || count($module) == 0) {
			throw new \Poppy\Framework\Exceptions\ModuleNotFoundException($slug);
		}

		$namespace = studly_case($module['slug']);
		if ($class) {
			return "{$namespace}\\{$class}";
		}
		else {
			return "{$namespace}";
		}

	}
}
