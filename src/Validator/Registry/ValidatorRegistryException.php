<?php

namespace Rezouce\Validator\Validator\Registry;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

class ValidatorRegistryException extends RuntimeException implements NotFoundExceptionInterface
{
}
