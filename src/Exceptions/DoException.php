<?php namespace Poppy\Framework\Exceptions;

use Poppy\Framework\Classes\Resp;

class DoException extends \Exception
{
	public function __construct($message = '')
	{
		if ($message instanceof Resp) {
			$message = $message->getMessage();
		}
		$this->message = 'Do Exception:' . $message;
	}
}