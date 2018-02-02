<?php

namespace Rezouce\Validator\Rule;

use Psr\Container\ContainerInterface;
use Rezouce\Validator\ValidationResult;
use Rezouce\Validator\Validator\MandatoryValidatorInterface;

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
        if (isset($data[$this->name]) || $this->hasMandatoryRules($registry)) {
            $errors = $this->validateRules($data, $registry);
        }

        return new ValidationResult(
            empty($errors) ? [$this->name => $data[$this->name] ?? null] : [],
            empty($errors) ? [] : [$this->name => $errors]
        );
    }

    private function hasMandatoryRules(ContainerInterface $registry): bool
    {
        return !empty(array_filter($this->rules, function(Rule $rule) use ($registry) {
            
            return $registry->get(($rule->getName())) instanceof MandatoryValidatorInterface;
        }));
    }

    private function validateRules(array $data, ContainerInterface $registry): array
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

        return $errors;
    }
}
