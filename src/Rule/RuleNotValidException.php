<?php

namespace Rezouce\Validator\Rule;

use LogicException;
use Rezouce\Validator\ValidatorExceptionInterface;

class RuleNotValidException extends LogicException implements ValidatorExceptionInterface
{
}
