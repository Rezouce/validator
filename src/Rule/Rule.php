<?php

namespace Rezouce\Validator\Rule;

class Rule
{
    private $name;

    private $options;

    public function __construct(string $name, array $options = [])
    {
        $this->name = $name;
        $this->options = $options;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
