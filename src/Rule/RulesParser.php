<?php

namespace Rezouce\Validator\Rule;

class RulesParser
{
    /**
     * @param mixed $rules
     * @return Rule[]
     */
    public function parse($rules): array
    {
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        $rules = (array)$rules;

        $parsedRules = [];

        foreach ($rules as $rule) {
            $parsedRules[] = $this->createRule($rule);
        }

        return $parsedRules;
    }

    private function createRule($rule): Rule
    {
        if ($rule instanceof Rule) {
            return $rule;
        }

        return (new RuleParser)->parse($rule);
    }
}
