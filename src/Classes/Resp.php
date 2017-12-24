<?php namespace Poppy\Framework\Classes;


use Poppy\Framework\Helper\ArrHelper;
use Poppy\Framework\Helper\StrHelper;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\MessageBag;

class Resp
{
	const SUCCESS       = 0;
	const ERROR         = 1;
	const TOKEN_MISS    = 2;
	const TOKEN_TIMEOUT = 3;
	const TOKEN_ERROR   = 4;
	const PARAM_ERROR   = 5;
	const SIGN_ERROR    = 6;
	const NO_AUTH       = 7;
	const INNER_ERROR   = 99;

	const WEB_SUCCESS = 'success';
	const WEB_ERROR   = 'error';


	private $code    = 1;
	private $message = '操作出错了';

	public function __construct($code, $message = '')
	{
		// init
		if (!$code) {
			$code = self::SUCCESS;
		}

		$this->code = intval($code);

		if (is_string($message) && !empty($message)) {
			$this->message = $message;
		}

		if ($message instanceof MessageBag) {
			$formatMessage = [];
			foreach ($message->all(':message') as $msg) {
				$formatMessage [] = $msg;
			}
			$this->message = $formatMessage;
		}

		if (!$message) {
			switch ($code) {
				case self::SUCCESS:
					$message = trans('poppy::resp.success');
					break;
				case self::ERROR:
					$message = trans('poppy::resp.error');
					break;
				case self::TOKEN_MISS:
					$message = trans('poppy::resp.token_miss');
					break;
				case self::TOKEN_TIMEOUT:
					$message = trans('poppy::resp.token_timeout');
					break;
				case self::TOKEN_ERROR:
					$message = trans('poppy::resp.token_error');
					break;
				case self::PARAM_ERROR:
					$message = trans('poppy::resp.param_error');
					break;
				case self::SIGN_ERROR:
					$message = trans('poppy::resp.sign_error');
					break;
				case self::NO_AUTH:
					$message = trans('poppy::resp.no_auth');
					break;
				case self::INNER_ERROR:
				default:
					$message = trans('poppy::resp.inner_error');
					break;
			}
			$this->message = $message;
		}
	}


	/**
	 * 返回错误代码
	 * @return int
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * 返回错误信息
	 * @return null|string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * 错误输出
	 * @param int                     $type  错误码
	 * @param string|array|MessageBag $msg   类型
	 * @param string                  $append
	 *                                       json: 强制以 json 数据返回
	 *                                       forget : 不将错误信息返回到session 中
	 *                                       location : 重定向
	 *                                       reload : 刷新页面
	 *                                       time   : 刷新或者重定向的时间(毫秒), 如果不填写, 默认为立即刷新或者重定向
	 *                                       reload_opener : 刷新母窗口
	 * @param array                   $input 表单提交的数据, 是否连带返回
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
	 */
	public static function web($type, $msg, $append = null, $input = null)
	{
		$resp     = self::create($type, $msg);
		$isJson   = false;
		$isForget = false;

		$arrAppend = StrHelper::parseKey($append);

		// is json
		if (isset($arrAppend['json']) || \Request::ajax()) {
			$isJson = true;
			unset($arrAppend['json']);
		}

		// is forget
		if (isset($arrAppend['forget'])) {
			$isForget = true;
			unset($arrAppend['forget']);
		}

		$append   = ArrHelper::genKey($arrAppend);
		$location = isset($arrAppend['location']) ? $arrAppend['location'] : '';
		$time     = isset($arrAppend['time']) ? $arrAppend['time'] : 0;

		if ($isJson) {
			return self::webSplash($resp, $append, $input);
		}
		else {
			if (!$isForget) {
				\Session::flash('end.message', $resp->getMessage());
				\Session::flash('end.level', $resp->getCode());
			}
			if (isset($arrAppend['reload'])) {
				$location = \Session::previousUrl();
			}
			return self::webView($time, $location, $input);
		}
	}


	public function __toString()
	{
		if (is_array($this->message)) {
			return implode("\n", $this->message);
		}
		else {
			return $this->message;
		}
	}

	public function toArray()
	{
		return [
			'status'  => $this->getCode(),
			'message' => $this->getMessage(),
		];
	}

	/**
	 * 显示界面
	 * @param $time
	 * @param $location
	 * @param $input
	 * @return \Illuminate\Http\RedirectResponse|Resp
	 */
	private static function webView($time, $location, $input)
	{
		if ($time || $location == 'back' || $location == 'message' || !$location) {
			$re   = $location ?: 'back';
			$view = 'template.message';
			return response()->view('poppy::' . $view, [
				'location' => $re,
				'input'    => $input,
				'time'     => isset($time) ? $time : 0,
			]);
		}
		else {
			$re = ($location && $location != 'back') ? \Redirect::to($location) : \Redirect::back();
			return $input ? $re->withInput($input) : $re;
		}
	}

	/**
	 * 不支持 location
	 * splash 不支持 location | back (Mark Zhao)
	 * @param Resp   $resp
	 * @param string $append
	 * @param array  $input
	 * @return \Illuminate\Http\Response
	 */
	private static function webSplash($resp, $append = '', $input = [])
	{
		$return = [
			'status'  => $resp->getCode(),
			'message' => $resp->getMessage(),
		];

		$data = [];
		if (!is_null($append)) {
			if ($append instanceof Arrayable) {
				$data = $append->toArray();
			}
			if (is_string($append)) {
				$data = StrHelper::parseKey($append);
			}

			if (isset($data['location']) && $data['location'] == 'back') {
				unset($data['location']);
			}
		}
		if ($data) {
			$return['data'] = (array) $data;
		}

		if (is_array($input) && $input) {
			\Session::flashInput($input);
		}

		$json = json_encode($return, JSON_UNESCAPED_UNICODE);
		return \Response::make($json);
	}


	/**
	 * @param int                    $code
	 * @param Resp|string|MessageBag $msg
	 * @return Resp
	 */
	public static function create($code, $msg)
	{
		$create = function ($code, $msg, $err_msg = null) {
			return new Resp([
				'code' => $code,
				'msg'  => $msg,
			], $err_msg);
		};
		if ($msg instanceof MessageBag) {
			$msg = $create(self::PARAM_ERROR, trans('poppy::resp.param_error'), $msg);
		}

		// parse string
		if (is_string($msg)) {
			$msg = $create($code, $msg);
		}

		// 默认提示, 内部错误
		if (!($msg instanceof Resp)) {
			$msg = $create(self::INNER_ERROR, trans('poppy::resp.inner_error'), $msg);
		}
		return $msg;
	}

}

