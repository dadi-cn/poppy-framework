<?php namespace Poppy\Framework\Helper;


/**
 * 缓存相关操作
 */
class CacheHelper
{


	/**
	 * 缓存 key 生成, 根据类名
	 * @param string $class
	 * @param string $suffix
	 * @return string
	 */
	public static function name($class, $suffix = '')
	{
		$snake = str_replace('\\', '', snake_case(lcfirst($class)));
		return $suffix ? $snake . '_' . $suffix : $snake;
	}
}