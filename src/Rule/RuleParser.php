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

        return new Rule($name, (new RuleOptionsParser())->parse($rule));
    }
}
