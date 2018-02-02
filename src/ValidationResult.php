<?php

namespace Rezouce\Validator;

class ValidationResult
{
    /** @var array */
    private $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    public function getErrorMessages(): array
    {
        return $this->errors;
    }
}
