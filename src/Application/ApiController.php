<?php namespace Poppy\Framework\Application;


use Illuminate\Container\Container;

class ApiController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		Container::getInstance()->setExecutionContext('api');
	}
}