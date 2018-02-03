<?php

namespace Rezouce\Validator\Validator\RespectValidator;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validatable;
use Respect\Validation\Validator as Validator;
use Rezouce\Validator\Validator\ValidatorInterface;

class RespectValidator implements ValidatorInterface
{
    private $rule;

    /** @var Validatable */
    private $validator;

    private $options;

    private $errorMessage = '';

    public function __construct(string $rule)
    {
        $this->rule = $rule;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    private function getValidator()
    {
        if (null === $this->validator) {
            $this->validator = call_user_func_array([Validator::class, $this->rule], $this->options);
        }

        return $this->validator;
    }

    public function validate($data): bool
    {
        try {
            return $this->getValidator()->assert($data);
        } catch(NestedValidationException $e) {
            $this->errorMessage = implode(',', $e->getMessages());
        }

        return false;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}