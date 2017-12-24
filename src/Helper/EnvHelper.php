<?php namespace Poppy\Framework\Helper;

/*
| This is NOT a Free software.
| When U have some Question or Advice can contact Me.
| @author     Mark <zhaody901@126.com>
| @copyright  Copyright (c) 2013-2017 Sour Lemon Team
*/

/**
 * 环境获取
 */
class EnvHelper
{

	/**
	 * @return string 返回IP
	 */
	public static function ip()
	{
		isset($_SERVER['HTTP_X_FORWARDED_FOR']) or $_SERVER['HTTP_X_FORWARDED_FOR'] = '';
		isset($_SERVER['REMOTE_ADDR']) or $_SERVER['REMOTE_ADDR'] = '';
		isset($_SERVER['HTTP_CLIENT_IP']) or $_SERVER['HTTP_CLIENT_IP'] = '';
		if ($_SERVER['HTTP_X_FORWARDED_FOR'] && $_SERVER['REMOTE_ADDR']) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			if (strpos($ip, ',') !== false) {
				$tmp = explode(',', $ip);
				$ip  = trim(end($tmp));
			}
			if (UtilHelper::isIp($ip)) return $ip;
		}
		if (UtilHelper::isIp($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
		if (UtilHelper::isIp($_SERVER['REMOTE_ADDR'])) return $_SERVER['REMOTE_ADDR'];
		return 'unknown';
	}

	/**
	 * @return string 当前文件的名称
	 */
	public static function self()
	{
		return $_SERVER['PHP_SELF']
			?? $_SERVER['SCRIPT_NAME']
			?? $_SERVER['ORIG_PATH_INFO'];
	}

	/**
	 * @return string 来源地址
	 */
	public static function referer()
	{
		return $_SERVER['HTTP_REFERER'] ?? '';
	}

	/**
	 * @return string 返回服务器名称
	 */
	public static function domain()
	{
		return $_SERVER['SERVER_NAME'];
	}

	/**
	 * @return string 协议名称
	 */
	public static function scheme()
	{
		if (!isset($_SERVER['SERVER_PORT'])) {
			return 'http://';
		}
		return $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	}

	/**
	 * @return string 返回端口号
	 */
	public static function port()
	{
		if (!isset($_SERVER['SERVER_PORT'])) {
			return '';
		}
		return $_SERVER['SERVER_PORT'] == '80' ? '' : ':' . $_SERVER['SERVER_PORT'];
	}

	/**
	 * @return string 完整的地址
	 */
	public static function uri()
	{
		if (isset($_SERVER['REQUEST_URI'])) {
			$uri = $_SERVER['REQUEST_URI'];
		}
		else {
			$uri = $_SERVER['PHP_SELF'];
			if (isset($_SERVER['argv'])) {
				if (isset($_SERVER['argv'][0])) $uri .= '?' . $_SERVER['argv'][0];
			}
			else {
				$uri .= '?' . $_SERVER['QUERY_STRING'];
			}
		}
		$uri = StrHelper::htmlSpecialChars($uri);
		return self::scheme() . self::host() . (strpos(self::host(), ':') === false ? self::port() : '') . $uri;
	}

	/**
	 * 获取主机
	 * @return string
	 */
	public static function host()
	{
		return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
	}

	/**
	 * @return string   没有查询的完整的URL地址, 基于当前页面
	 */
	public static function nquri()
	{
		return self::scheme() . self::host() . (strpos(self::host(), ':') === false ? self::port() : '') . self::self();
	}

	/**
	 * 请求的unix 时间戳
	 * @return int
	 */
	public static function time()
	{
		return $_SERVER['REQUEST_TIME'];
	}


	/**
	 * 浏览器头部
	 * @return mixed
	 */
	public static function agent()
	{
		return $_SERVER['HTTP_USER_AGENT'];
	}

	/**
	 * 是否是代理
	 * @return bool
	 */
	public static function isProxy()
	{
		return
			(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ||
			isset($_SERVER['HTTP_VIA']) ||
			isset($_SERVER['HTTP_PROXY_CONNECTION']) ||
			isset($_SERVER['HTTP_USER_AGENT_VIA']) ||
			isset($_SERVER['HTTP_CACHE_INFO']);
	}

	/**
	 * 是否win 服务器
	 * @return bool
	 */
	public static function isWindows()
	{
		if (strtoupper(PHP_OS) == 'DARWIN') {
			return false;
		}
		return strpos(strtoupper(PHP_OS), 'WIN') !== false ? true : false;
	}

	/**
	 * 获取客户端OS
	 * @return array|string
	 */
	public static function os()
	{
		$agent = self::agent();
		if (preg_match('/win/i', $agent)) {
			$os = 'windows';
		}
		elseif (preg_match('/linux/i', $agent)) {
			$os = 'linux';
		}
		elseif (preg_match('/unix/i', $agent)) {
			$os = 'unix';
		}
		elseif (preg_match('/mac/i', $agent)) {
			$os = 'Macintosh';
		}
		else {
			$os = 'other';
		}
		return $os;
	}

}