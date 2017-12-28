<?php namespace Poppy\Framework\Validation;

use Illuminate\Validation\Rule as IlluminateRule;

/**
 * Class Rule.
 */
class Rule extends IlluminateRule
{
	/**
	 * @return string
	 */
	public static function array()
	{
		return 'array';
	}

	/**
	 * alpha rule
	 * @return string
	 */
	public static function alpha()
	{
		return 'alpha';
	}

	/**
	 * string rule
	 * @return string
	 */
	public static function string()
	{
		return 'string';
	}

	/**
	 * @return string
	 */
	public static function boolean()
	{
		return 'boolean';
	}

	/**
	 * @param $format
	 * @return string
	 */
	public static function dateFormat($format)
	{
		return 'date_format:' . $format;
	}

	/**
	 * @return string
	 */
	public static function email()
	{
		return 'email';
	}

	/**
	 * @return string
	 */
	public static function file()
	{
		return 'file';
	}

	/**
	 * @return string
	 */
	public static function image()
	{
		return 'image';
	}

	/**
	 * @param array $mimeTypes
	 * @return string
	 */
	public static function mimetypes(array $mimeTypes)
	{
		return 'mimetypes:' . implode(',', $mimeTypes);
	}

	/**
	 * @return string
	 */
	public static function numeric()
	{
		return 'numeric';
	}

	/**
	 * @param $regex
	 * @return string
	 */
	public static function regex($regex)
	{
		return 'regex:' . $regex;
	}

	/**
	 * @return string
	 */
	public static function required()
	{
		return 'required';
	}

	/**
	 * @return string
	 */
	public static function mobile()
	{
		return 'mobile';
	}

	/**
	 * @return string
	 */
	public static function password()
	{
		return 'password';
	}

	/**
	 * @return string
	 */
	public static function url()
	{
		return 'url';
	}

	/**
	 * Between String
	 * @param $start
	 * @param $end
	 * @return string
	 */
	public static function between($start, $end)
	{
		return 'between:' . $start . ',' . $end;
	}

	/**
	 * @return string
	 */
	public static function integer()
	{
		return 'integer';
	}
}
