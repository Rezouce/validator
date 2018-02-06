<?php

namespace Rezouce\Validator\Rule;

class RuleOptionsParser
{
    public function parse(array $options): array
    {
        return array_map(function ($option) {
            return $this->isValidStringOption($option)
                ? $this->parseOptionFromString($option)
                : $option;
        }, $options ?: []);
    }

    private function isValidStringOption($option): bool
    {
        return is_string($option) && !empty($option);
    }

    /**
     * When there is only one option provided, we return this options instead
     * of an array containing it.
     */
    private function parseOptionFromString(string $option)
    {
        $optionValues = array_map(function ($data) {
            return $this->castValue($data);
        }, explode(',', $option));

        return count($optionValues) > 1
            ? $optionValues
            : current($optionValues);
    }

    /**
     * When the options' values are provided as strings, we need to evaluate
     * the values to determinate if they can be cast as numeric values.
     */
    private function castValue($value)
    {
        return is_numeric($value) ? 0 + $value : $value;
    }
}
