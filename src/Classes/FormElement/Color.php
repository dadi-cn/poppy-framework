<?php namespace Poppy\Framework\Classes\FormElement;


use Poppy\Framework\Contracts\FormElement as FormElementContract;

class Color extends FormTypeBase implements FormElementContract
{

	public function __construct($name, $setting, $value = null)
	{
		parent::__construct($name, $setting, $value);
	}

	/**
	 * 渲染HTML
	 * @return mixed
	 */
	public function render()
	{
		return \Form::color($this->name, $this->value, $this->form_options);
	}
}