<?php namespace Poppy\Framework\Classes\Traits;

use Illuminate\Support\Str;

/**
 * Trait Viewable.
 */
trait ViewTrait
{
	/**
	 * Share variable with view.
	 *
	 * @param      $key
	 * @param null $value
	 */
	protected function share($key, $value = null)
	{
		$this->getView()->share($key, $value);
	}

	/**
	 * Share variable with view.
	 *
	 * @param       $template
	 * @param array $data
	 * @param array $mergeData
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	protected function view($template, array $data = [], $mergeData = [])
	{
		if (Str::contains($template, '::')) {
			return $this->getView()->make($template, $data, $mergeData);
		}
		 
			return $this->getView()->make('theme::' . $template, $data, $mergeData);
	}
}