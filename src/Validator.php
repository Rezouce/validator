<?php

namespace Rezouce\Validator;

use Rezouce\Validator\Rule\RuleStack;

class Validator
{
    /** @var RuleStack[] */
    private $rules = [];

    public function __construct(array $rules)
    {
        $this->createRules($rules);
    }

    public function validate(): ValidationResult
    {
        return new ValidationResult();
    }

    private function createRules($rules)
    {
        foreach ($rules as $dataName => $dataRules) {
            $this->rules[] = new RuleStack($dataName, $dataRules);
        }
    }
}
