<?php

namespace Rezouce\Validator;

class ValidationResult
{
    public function isValid(): bool
    {
        return true;
    }

    public function getErrorMessages(): array
    {
        return [];
    }
}
