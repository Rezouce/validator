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
            $rule = explode(':', $rule);
        }

        $name = array_shift($rule);

        $options = array_map(function ($option) {
            if (is_string($option) && !empty($option)) {
                return explode(',', $option);
            }

            return $option;
        }, $rule ?: []);

        return new Rule($name, $options);
    }
}
