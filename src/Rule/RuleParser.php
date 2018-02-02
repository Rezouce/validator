<?php

namespace Rezouce\Validator\Rule;

class RuleParser
{
    public function parse($rule): Rule
    {
        if ($rule instanceof Rule) {
            return $rule;
        }

        if (is_string($rule)) {
            $rule =  explode(':', $rule);
        }

        [$name, $options] = array_merge($rule, ['']);

        if (is_string($options)) {
            $options = empty($options)
                ? []
                : explode(',', $options);
        }

        return new Rule($name, $options);
    }
}
