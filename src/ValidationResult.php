<?php

namespace Rezouce\Validator;

class ValidationResult
{
    private $data;

    private $errors;

    public function __construct(array $data, array $errors)
    {
        $this->data = $data;
        $this->errors = $errors;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getErrorMessages(): array
    {
        return $this->errors;
    }
}
