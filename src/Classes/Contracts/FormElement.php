<?php namespace Poppy\Framework\Classes\Contracts;

interface FormElement
{

	/**
	 * 渲染HTML
	 * @return mixed
	 */
	function render();

	/**
	 * 获取默认值
	 * @return mixed
	 */
	function defaultValue();


	function value();

	function label();

	function tip();

}