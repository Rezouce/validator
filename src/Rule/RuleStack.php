<?php

namespace Rezouce\Validator\Rule;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Rezouce\Validator\ValidationResult;
use Rezouce\Validator\Validator\ValidatorInterface;
use Rezouce\Validator\ValidatorExceptionInterface;

class RuleStack
{
    private $name;

    private $rules;

    private $container;

    public function __construct(string $name, $rules, ContainerInterface $container)
    {
        $this->name = $name;

        $this->rules = (new RulesParser)->parse($rules);

        $this->container = $container;
    }

    /**
     * @throws RuleNotValidException
     * @throws ValidatorExceptionInterface
     */
    public function validate(array $data): ValidationResult
    {
        $dataParser = new DataParser();
        $dataCollection = $dataParser->parse($data, $this->name);

        $hasMandatoryRules = $this->hasMandatoryRules();

        $errors = [];
        $validatedData = [];

        foreach ($dataCollection as $data) {
            if ($data->isNull() && !$hasMandatoryRules) {
                $validatedData = $this->extractValidatedData($validatedData, $data);
            } else {
                $newErrors = $this->validateRules($data);

                if (empty($newErrors)) {
                    $validatedData = $this->extractValidatedData($validatedData, $data);
                }

                $errors = array_merge($errors, $newErrors);
            }
        }

        return new ValidationResult($validatedData, $errors);
    }

    private function hasMandatoryRules(): bool
    {
        return !empty(array_filter($this->rules, function (Rule $rule) {
            try {
                /** @var ValidatorInterface $validator */
                $validator = $this->container->get(($rule->getName()));
            } catch (ContainerExceptionInterface $e) {
                throw new RuleNotValidException(sprintf(
                    'No validator has been found for rule %s when validating field %s.',
                    $rule->getName(),
                    $this->name
                ), $e->getCode(), $e);
            }

            return $validator->isMandatory();
        }));
    }

    private function validateRules(Data $data): array
    {
        $errors = [];

        foreach ($this->rules as $rule) {
            /** @var ValidatorInterface $validator */
            $validator = $this->container->get($rule->getName());

            if (method_exists($validator, 'setOptions')) {
                $validator->setOptions($rule->getOptions());
            }

            $validation = $validator->validate($data->getData());

            if (!$validation->isValid()) {
                $errors = array_merge($errors, [$data->getKey() => $validation->getErrorMessages()]);
            }
        }

        return $errors;
    }

    private function extractValidatedData(array $validatedData, Data $data): array
    {
        $currentLevel = &$validatedData;

        foreach (explode('.', $data->getKey()) as $level) {
            $currentLevel = &$currentLevel[$level];
        }

        $currentLevel = $data->getData();

        return $validatedData;
    }
}
