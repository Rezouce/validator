<?php

namespace Rezouce\Validator\Validator;

use Rezouce\Validator\ValidationResult;

interface ValidatorInterface
{
    /**
     * @throws ValidatorException should be thrown when the validation cannot be performed.
     */
    public function validate($data): ValidationResult;

    public function isMandatory(): bool;
}
