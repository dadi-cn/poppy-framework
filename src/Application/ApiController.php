<?php namespace Poppy\Framework\Application;

use Dingo\Api\Routing\Helpers as DingoHelpers;
use Poppy\Framework\Application\Traits\PoppyTrait;

class ApiController extends Controller
{
	use DingoHelpers;
	use PoppyTrait;
}