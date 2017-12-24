<?php namespace Poppy\Tests\Helper;

use Poppy\Framework\Helper\ArrHelper;
use PHPUnit\Framework\TestCase;

class ArrHelperTest extends TestCase
{
	public function testCombine()
	{
		$arr     = [
			1, 2, 3, [4, 5], 6, 7,
		];
		$combine = ArrHelper::combine($arr);
		$this->assertEquals('1,2,3,4,5,6,7', $combine);
	}

	public function testGenKey()
	{
		$arr    = [
			'location' => 'http://www.baidu.com',
			'status'   => 'error',
		];
		$genKey = ArrHelper::genKey($arr);

		// 组合数组
		$this->assertEquals('location|http://www.baidu.com;status|error', $genKey);

		// 组合空
		$this->assertEquals('', ArrHelper::genKey([]));
	}
}