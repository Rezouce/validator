<?php

namespace Rezouce\Validator\Validator\Registry;

use Psr\Container\ContainerInterface;
use Rezouce\Validator\Validator\ValidatorInterface;

class ValidatorRegistry implements ContainerInterface
{
    public $validators = [];

    /**
     * @throws ValidatorRegistryException
     */
    public function get($id): ValidatorInterface
    {
        if (!$this->has($id)) {
            throw new ValidatorRegistryException(sprintf('No validator has been found for rule %s.', $id));
        }

        return $this->validators[$id];
    }

    public function has($id): bool
    {
        return isset($this->validators[$id]);
    }

    public function add(string $id, ValidatorInterface $validator): self
    {
        $this->validators[$id] = $validator;

        return $this;
    }
}
