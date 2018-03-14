<?php namespace Poppy\Framework\Http\Pagination;

class PageInfo
{
	private $page = 1;

	private $size = 15;

	public function __construct($page_info)
	{
		$sizeConfig = abs(config('poppy.pagesize')) ?: 20;
		$page       = abs($page_info['page'] ?? 1);
		$size       = abs($page_info['size'] ?? $sizeConfig);
		$this->page = $page ?: 1;
		$this->size = $size ?: $sizeConfig;
	}

	public function size()
	{
		return $this->size;
	}

	public function page()
	{
		return $this->page;
	}
}