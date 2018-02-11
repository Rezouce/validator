<?php

namespace Rezouce\Validator\Test\Rule;

use PHPUnit\Framework\TestCase;
use Rezouce\Validator\Rule\Rule;
use Rezouce\Validator\Rule\RuleNotValidException;
use Rezouce\Validator\Rule\RuleStack;
use Rezouce\Validator\Validator\RespectValidator\RespectValidationContainer;

class RuleStackTest extends TestCase
{
    /** @test */
    public function it_can_perform_the_validation_of_simple_valid_data()
    {
        $ruleStack = new RuleStack('email', [
            new Rule('notOptional'), new Rule('email')
        ], new RespectValidationContainer);

        $validation = $ruleStack->validate(['email' => 'contact@rezouce.net']);

        $this->assertTrue($validation->isValid());
        $this->assertEmpty($validation->getErrorMessages());
        $this->assertEquals(['email' => 'contact@rezouce.net'], $validation->getData());
    }

    /** @test */
    public function it_can_perform_the_validation_of_simple_invalid_data()
    {
        $ruleStack = new RuleStack('email', [
            new Rule('notOptional'), new Rule('email')
        ], new RespectValidationContainer);

        $validation = $ruleStack->validate(['email' => 'test']);

        $this->assertFalse($validation->isValid());
        $this->assertEquals([
            'email' => ['"test" must be valid email'],
        ], $validation->getErrorMessages());
        $this->assertEmpty($validation->getData());
    }

    /** @test */
    public function it_can_perform_the_validation_of_complex_valid_data()
    {
        $ruleStack = new RuleStack('users.*.name', [new Rule('alpha')], new RespectValidationContainer);

        $validation = $ruleStack->validate([
            'users' => [['name' => 'Rezouce']],
        ]);

        $this->assertTrue($validation->isValid());
        $this->assertEmpty($validation->getErrorMessages());
        $this->assertEquals([
            'users' => [['name' => 'Rezouce']],
        ], $validation->getData());
    }

    /** @test */
    public function it_can_perform_the_validation_of_complex_invalid_data()
    {
        $ruleStack = new RuleStack('users.*.email', [new Rule('email')], new RespectValidationContainer);

        $validation = $ruleStack->validate([
            'users' => [['email' => 'invalid email']],
        ]);

        $this->assertFalse($validation->isValid());
        $this->assertEquals([
            'users.0.email' => ['"invalid email" must be valid email'],
        ], $validation->getErrorMessages());
        $this->assertEmpty($validation->getData());
    }

    /** @test */
    public function it_validates_the_rules_only_when_necessary()
    {
        $ruleStack = new RuleStack('email', [new Rule('email')], new RespectValidationContainer);

        $validation = $ruleStack->validate([]);

        $this->assertTrue($validation->isValid());
        $this->assertEmpty($validation->getErrorMessages());
        $this->assertEquals(['email' => null], $validation->getData());
    }

    /** @test */
    public function it_throws_an_exception_if_a_rule_cannot_be_found()
    {
        $ruleStack = new RuleStack('email', [new Rule('inexisting')], new RespectValidationContainer);

        $this->expectException(RuleNotValidException::class);
        $this->expectExceptionMessage('No validator has been found for rule inexisting when validating field email.');

        $ruleStack->validate([]);
    }
}
