<?php namespace Poppy\Framework\Classes\Contracts;

interface FormElement
{
	/**
	 * 渲染HTML
	 * @return mixed
	 */
	public function render();

	/**
	 * 获取默认值
	 * @return mixed
	 */
	public function defaultValue();

	public function value();

	public function label();

	public function tip();
}