<?php

namespace Rezouce\Validator\Rule;

use Psr\Container\ContainerInterface;
use Rezouce\Validator\ValidationResult;

class RuleStack
{
    private $name;

    private $rules;

    public function __construct(string $name, $rules)
    {
        $this->name = $name;

        $this->rules = (new RulesParser)->parse($rules);
    }

    public function validate(array $data, ContainerInterface $registry): ValidationResult
    {
        $errors = [];

        foreach ($this->rules as $rule) {
            $validator = $registry->get($rule->getName());

            if (method_exists($validator, 'setOptions')) {
                $validator->setOptions($rule->getOptions());
            }

            if (!$validator->validate($data[$this->name] ?? null)) {
                $errors[] = $validator->getErrorMessage();
            }
        }

        return new ValidationResult(empty($errors) ? [] : [$this->name => $errors]);
    }
}
