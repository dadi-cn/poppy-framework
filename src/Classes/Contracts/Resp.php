<?php namespace Poppy\Framework\Classes\Contracts;

// todo
interface Resp
{

	/**
	 * 返回响应
	 * @param int        $code    状态码
	 * @param string     $message 提示的消息
	 * @param null|array $data    返回的data 数据
	 * @param null|array $append  追加的数据
	 * @return \Illuminate\Http\JsonResponse
	 */
	public static function show($code, $message = '', $data = null, $append = null);

}