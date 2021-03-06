<?php

namespace Rezouce\Validator;

use Psr\Container\ContainerInterface;
use Rezouce\Validator\Rule\RuleNotValidException;
use Rezouce\Validator\Rule\RuleStack;

class Validator
{
    /** @var RuleStack[] */
    private $rules = [];

    public function __construct(array $rules, ContainerInterface $container)
    {
        $this->createRules($rules, $container);
    }

    /**
     * @throws RuleNotValidException
     * @throws ValidatorExceptionInterface
     */
    public function validate(array $data): ValidationResult
    {
        $validatedData = [];
        $errors = [];

        foreach ($this->rules as $ruleStack) {
            $validation = $ruleStack->validate($data);

            $validatedData = array_replace_recursive($validatedData, $validation->getData());
            $errors = array_merge($errors, $validation->getErrorMessages());
        }

        return new ValidationResult($validatedData, $errors);
    }

    private function createRules(array $rules, ContainerInterface $container)
    {
        foreach ($rules as $dataName => $dataRules) {
            $this->rules[] = new RuleStack($dataName, $dataRules, $container);
        }
    }
}
