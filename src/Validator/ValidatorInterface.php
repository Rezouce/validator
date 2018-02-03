<?php

namespace Rezouce\Validator\Validator;

interface ValidatorInterface
{
    public function validate($data): bool;

    public function getErrorMessage(): string;

    public function isMandatory(): bool;
}
