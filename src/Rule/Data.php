<?php

namespace Rezouce\Validator\Rule;

class Data
{
    private $data;
    private $key;

    public function __construct($data, string $key)
    {
        $this->data = $data;
        $this->key = $key;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function isNull(): bool
    {
        return null === $this->data;
    }
}
