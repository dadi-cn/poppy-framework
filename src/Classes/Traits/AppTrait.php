<?php namespace Poppy\Framework\Classes\Traits;


use Poppy\Framework\Classes\Resp;


trait AppTrait
{

	/** @var Resp */
	protected $error;

	/** @var Resp */
	protected $success;

	/**
	 * 设置错误
	 * @param $error
	 * @return bool
	 */
	public function setError($error)
	{
		$this->error = new Resp(Resp::ERROR, $error);
		return false;
	}

	/**
	 * 获取错误
	 * @return Resp
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * Get success messages;
	 * @param string $message
	 * @return Resp
	 */
	public function getSuccess($message = '')
	{
		return (new Resp(Resp::SUCCESS, $message));
	}


}