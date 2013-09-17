<?php

namespace MxcRoutes\Exception;

use Zend\Mvc\Exception;
use Zend\Http\Exception\ExceptionInterface;

class InvalidArgumentException extends Exception\InvalidArgumentException implements ExceptionInterface
{
}
