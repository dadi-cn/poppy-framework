<?php namespace Poppy\Framework\Classes;


use \Poppy\Framework\Contracts\FormElement as FormElementContract;


class FormElement
{
	const TEXT          = 'text';
	const COLOR         = 'color';
	const THUMB         = 'thumb';
	const MULTI_IMAGE   = 'multi_image';
	const TEXTAREA      = 'textarea';
	const AREA          = 'area';
	const DATE          = 'date';
	const DATETIME      = 'datetime';
	const MESSAGE       = 'message';
	const MAP           = 'map';
	const MULTI_SELECT  = 'multi_select';
	const SINGLE_SELECT = 'single_select';
	const TYPE          = 'type';
	const EDITOR        = 'editor';
	const FILE          = 'file';

	public static function create($formType, $name, $setting, $default = null)
	{
		$class = '\\Duoli\\L5Base\\Classes\\FormElement' . ucfirst(camel_case($formType));
		/** @type FormElementContract $object */
		return new $class($name, $setting, $default);
	}
}