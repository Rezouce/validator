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
    public function it_validates_against_the_respect_validation_matching_the_provided_rule(
        string $rule,
        array $options,
        $testedData,
        $expectedResult
    ) {
        $validator = new RespectValidator($rule);
        $validator->setOptions($options);

        $this->assertEquals($expectedResult, $validator->validate($testedData)->isValid());
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
    public function it_returns_an_error_message_created_from_the_respect_validation_matching_the_provided_rule(
        string $rule,
        array $options,
        $testedData,
        $expectedResult
    ) {
        $validator = new RespectValidator($rule);
        $validator->setOptions($options);

        $this->assertEquals($expectedResult, $validator->validate($testedData)->getErrorMessages());
    }

    public function getErrorMessageProvider()
    {
        return [
            ['between', [10, 20, true], 15, []],
            ['between', [10, 20, true], 5, ['5 must be greater than or equal to 10']],
            ['in', ['red', 'green', 'blue'], 'red', []],
            ['in', ['red', 'green', 'blue'], 'yellow', ['"yellow" must be in "red"']],
            ['fibonacci', [], 34, []],
            ['fibonacci', [], 6, ['6 must be a valid Fibonacci number']],
        ];
    }

    /**
     * @test
     * @dataProvider getMandatoryRulesProvider
     */
    public function it_has_mandatory_rules(
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
    public function it_can_ommit_the_options_to_validate_rules_that_doesnt_require_it()
    {
        $validator = new RespectValidator('nullType');

        $this->assertTrue($validator->validate(null)->isValid());
    }

    /** @test */
    public function it_throws_an_exception_when_the_rule_cannot_be_resolved()
    {
        $validator = new RespectValidator('inexisting');

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('No Respect\Validation validator has been found for rule inexisting.');

        $validator->validate('data');
    }
}
