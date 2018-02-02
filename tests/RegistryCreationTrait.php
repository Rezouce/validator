<?php

namespace Rezouce\Validator\Test;

use Rezouce\Validator\Validator\Registry\ValidatorRegistry;
use Rezouce\Validator\Validator\ValidatorInterface;

trait RegistryCreationTrait
{
    protected function createRegistry()
    {
        $registry = new ValidatorRegistry();

        $registry->add(
            'required',
            new class() implements ValidatorInterface
            {
                public function validate($data): bool
                {
                    return $data !== null;
                }

                public function getErrorMessage(): string
                {
                    return 'This field is required.';
                }
            }
        );

        $registry->add(
            'email',
            new class() implements ValidatorInterface
            {
                public function validate($data): bool
                {
                    return $data === null
                        || false !== filter_var($data, FILTER_VALIDATE_EMAIL);
                }

                public function getErrorMessage(): string
                {
                    return 'This field should be a valid email.';
                }
            }
        );

        $registry->add(
            'in',
            new class() implements ValidatorInterface
            {
                private $availableValues = [];

                public function setOptions(array $availableValues)
                {
                    $this->availableValues = $availableValues;
                }

                public function validate($data): bool
                {
                    return $data === null
                        || in_array($data, $this->availableValues);
                }

                public function getErrorMessage(): string
                {
                    return sprintf('You must provide one of "%s".', implode(', ', $this->availableValues));
                }
            }
        );

        return $registry;
    }
}
