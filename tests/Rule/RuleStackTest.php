<?php

namespace Rezouce\Validator\Test\Rule;

use PHPUnit\Framework\TestCase;
use Rezouce\Validator\Rule\Rule;
use Rezouce\Validator\Rule\RuleStack;
use Rezouce\Validator\Validator\RespectValidator\RespectValidationContainer;

class RuleStackTest extends TestCase
{
    /** @test */
    public function itCanPerformValidationOfValidData()
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
    public function itCanPerformValidationOfInvalidData()
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
    public function itValidatesTheRulesOnlyWhenNecessary()
    {
        $ruleStack = new RuleStack('email', [new Rule('email')], new RespectValidationContainer);

        $validation = $ruleStack->validate([]);

        $this->assertTrue($validation->isValid());
        $this->assertEmpty($validation->getErrorMessages());
        $this->assertEquals(['email' => null], $validation->getData());
    }
}
