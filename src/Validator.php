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
        $validatedData = [];
        $errors = [];

        foreach ($this->rules as $ruleStack) {
            $validation = $ruleStack->validate($data, $this->registry);

            $validatedData = array_merge($validatedData, $validation->getData());
            $errors = array_merge($errors, $validation->getErrorMessages());
        }

        return new ValidationResult($validatedData, $errors);
    }

    private function createRules($rules)
    {
        foreach ($rules as $dataName => $dataRules) {
            $this->rules[] = new RuleStack($dataName, $dataRules);
        }
    }
}
