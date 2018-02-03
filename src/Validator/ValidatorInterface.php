<?php

namespace Rezouce\Validator\Validator;

interface ValidatorInterface
{
    /**
     * @throws ValidatorException should be thrown when the validation cannot be performed.
     */
    public function validate($data): bool;

    public function getErrorMessage(): string;

    public function isMandatory(): bool;
}
