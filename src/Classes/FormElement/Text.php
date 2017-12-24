<?php namespace Poppy\Framework\Classes\FormElement;


use Poppy\Framework\Contracts\FormElement as FormElementContract;

class Text extends FormTypeBase implements FormElementContract
{


	public function __construct($name, $setting, $value = null)
	{
		parent::__construct($name, $setting, $value);
		$this->defaultValue                = isset($setting['text_default_value']) ? $setting['text_default_value'] : '';
		$this->placeholder                 = isset($setting['text_placeholder']) ? $setting['text_placeholder'] : '';
		$this->form_options['placeholder'] = $this->placeholder;
	}

	/**
	 * 渲染HTML
	 * @return mixed
	 */
	public function render()
	{
		return \Form::text($this->name, $this->value, $this->form_options);
	}

}