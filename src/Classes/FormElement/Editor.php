<?php namespace Poppy\Framework\Classes\FormElement;


use Poppy\Framework\Contracts\FormElement as FormElementContract;

class Editor extends FormTypeBase implements FormElementContract
{

	public function __construct($name, $setting, $value = null)
	{
		parent::__construct($name, $setting, $value);
		$this->defaultValue                = isset($setting['editor_default_value']) ? $setting['editor_default_value'] : '';
		$this->placeholder                 = isset($setting['editor_placeholder']) ? $setting['editor_placeholder'] : '';
		$this->form_options['placeholder'] = $this->placeholder;
	}

	/**
	 * 渲染HTML
	 * @return mixed
	 */
	public function render()
	{
		return \Form::kindeditor($this->name, $this->value, $this->form_options);
	}
}