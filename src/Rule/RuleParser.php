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
                return $this->getOptionsFromString($option);
            }

            return $option;
        }, $rule ?: []);

        return new Rule($name, $options);
    }

    /**
     * When there is only one option provided, we return this options instead
     * of an array containing it.
     */
    private function getOptionsFromString($option)
    {
        $options = array_map(function ($data) {
            return $this->castData($data);
        }, explode(',', $option));

        return count($options) > 1
            ? $options
            : current($options);
    }

    /**
     * When the data are provided by a string, we lost the original casting.
     * This method cast the numeric values from string to float/int.
     */
    private function castData($data)
    {
        return is_numeric($data) ? 0 + $data : $data;
    }
}
