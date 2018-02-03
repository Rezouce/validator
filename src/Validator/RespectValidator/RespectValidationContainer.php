<?php

namespace Rezouce\Validator\Validator\RespectValidator;

use Psr\Container\ContainerInterface;

class RespectValidationContainer implements ContainerInterface
{
    /**
     * @throws ResolverException
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new ResolverException(sprintf('No Respect\Validation validator has been found for rule %s.', $id));
        }

        return new RespectValidator($id);
    }

    public function has($id)
    {
        return class_exists('Respect\Validation\Rules\\' . ucfirst($id));
    }
}
