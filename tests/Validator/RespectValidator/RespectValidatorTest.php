<?php

namespace Rezouce\Validator\Test\Validator\RespectValidator;

use PHPUnit\Framework\TestCase;
use Rezouce\Validator\Validator\RespectValidator\RespectValidator;
use Rezouce\Validator\Validator\ValidatorException;

class RespectValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider getDataProvider
     */
    public function itValidatesAgainstTheRespectValidationMatchingTheProvidedRule(
        string $rule,
        array $options,
        $testedData,
        $expectedResult
    ) {
        $validator = new RespectValidator($rule);
        $validator->setOptions($options);

        $this->assertEquals($expectedResult, $validator->validate($testedData));
    }

    public function getDataProvider()
    {
        return [
            ['between', [10, 20, true], 15, true],
            ['between', [10, 20, true], 5, false],
            ['in', ['red', 'green', 'blue'], 'red', true],
            ['in', ['red', 'green', 'blue'], 'yellow', false],
            ['fibonacci', [], 34, true],
            ['fibonacci', [], 6, false],
        ];
    }

    /**
     * @test
     * @dataProvider getErrorMessageProvider
     */
    public function itReturnsAnErrorMessageCreateFromTheRespectValidationMatchingTheProvidedRule(
        string $rule,
        array $options,
        $testedData,
        $expectedResult
    ) {
        $validator = new RespectValidator($rule);
        $validator->setOptions($options);
        $validator->validate($testedData);

        $this->assertEquals($expectedResult, $validator->getErrorMessage());
    }

    public function getErrorMessageProvider()
    {
        return [
            ['between', [10, 20, true], 15, ''],
            ['between', [10, 20, true], 5, '5 must be greater than or equal to 10'],
            ['in', ['red', 'green', 'blue'], 'red', ''],
            ['in', ['red', 'green', 'blue'], 'yellow', '"yellow" must be in "red"'],
            ['fibonacci', [], 34, ''],
            ['fibonacci', [], 6, '6 must be a valid Fibonacci number'],
        ];
    }

    /**
     * @test
     * @dataProvider getMandatoryRulesProvider
     */
    public function itHasMandatoryRules(
        string $rule,
        $expectedResult
    ) {
        $validator = new RespectValidator($rule);

        $this->assertEquals($expectedResult, $validator->isMandatory());
    }

    public function getMandatoryRulesProvider()
    {
        return [
            ['between', false],
            ['in', false],
            ['notOptional', true],
            ['nullType', true],
            ['notBlank', true],
            ['notEmpty', true],
        ];
    }

    /** @test */
    public function testTheOptionsCanBeOmittedToValidateBasicRules()
    {
        $validator = new RespectValidator('nullType');

        $this->assertTrue($validator->validate(null));
    }

    /** @test */
    public function itThrowsAnExceptionWhenTheRuleCannotBeResolved()
    {
        $validator = new RespectValidator('inexisting');

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('No Respect\Validation validator has been found for rule inexisting.');

        $validator->validate('data');
    }
}
