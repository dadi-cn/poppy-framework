<?php

namespace Poppy\Framework\GraphQL\Support;

use Validator;
use Poppy\Framework\GraphQL\Error\ValidationError;
use Poppy\Framework\GraphQL\Support\Traits\ShouldValidate;

class Mutation extends Field
{
    use ShouldValidate;
}
