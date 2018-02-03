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
        if (isset($data[$this->name]) || $this->hasMandatoryRules()) {
            $errors = $this->validateRules($data);
        }

        return new ValidationResult(
            empty($errors) ? [$this->name => $data[$this->name] ?? null] : [],
            empty($errors) ? [] : [$this->name => $errors]
        );
    }

    private function hasMandatoryRules(): bool
    {
        return !empty(array_filter($this->rules, function(Rule $rule) {
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

    private function validateRules(array $data): array
    {
        $errors = [];

        foreach ($this->rules as $rule) {
            $validator = $this->container->get($rule->getName());

            if (method_exists($validator, 'setOptions')) {
                $validator->setOptions($rule->getOptions());
            }

            if (!$validator->validate($data[$this->name] ?? null)) {
                $errors[] = $validator->getErrorMessage();
            }
        }

        return $errors;
    }
}
