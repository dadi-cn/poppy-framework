<?php namespace Poppy\Framework\Classes\FormElement;


use Poppy\Framework\Contracts\FormElement as FormElementContract;


class Textarea extends FormTypeBase implements FormElementContract
{


	public function __construct($name, $setting, $value = null)
	{
		parent::__construct($name, $setting, $value);
		$this->defaultValue                = isset($setting['textarea_default_value']) ? $setting['textarea_default_value'] : '';
		$this->placeholder                 = isset($setting['textarea_placeholder']) ? $setting['textarea_placeholder'] : '';
		$this->form_options['placeholder'] = $this->placeholder;
	}

	/**
	 * 渲染HTML
	 * @return mixed
	 */
	public function render()
	{
		return \Form::textarea($this->name, $this->value, $this->form_options);
	}


}