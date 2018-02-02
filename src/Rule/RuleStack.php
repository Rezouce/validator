<?php

namespace Rezouce\Validator\Rule;

class RuleStack
{
    private $name;

    private $rules;

    public function __construct(string $name, $rules)
    {
        $this->name = $name;

        $this->rules = (new RulesParser)->parse($rules);
    }
}
