<?php

namespace Rezouce\Validator\Validator\RespectValidator;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

class ResolverException extends RuntimeException implements NotFoundExceptionInterface
{
}
