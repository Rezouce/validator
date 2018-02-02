<?php

namespace Rezouce\Validator;

use Psr\Container\ContainerInterface;
use Rezouce\Validator\Rule\RuleStack;

class Validator
{
    /** @var RuleStack[] */
    private $rules = [];

    private $registry;

    public function __construct(array $rules, ContainerInterface $registry)
    {
        $this->createRules($rules);
        $this->registry = $registry;
    }

    public function validate(array $data): ValidationResult
    {
        $errors = [];

        foreach ($this->rules as $ruleStack) {
            $errors = array_merge($errors, $ruleStack->validate($data, $this->registry)->getErrorMessages());
        }

        return new ValidationResult($errors);
    }

    private function createRules($rules)
    {
        foreach ($rules as $dataName => $dataRules) {
            $this->rules[] = new RuleStack($dataName, $dataRules);
        }
    }
}
