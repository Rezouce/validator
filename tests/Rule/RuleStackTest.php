<?php

namespace Rezouce\Validator\Test\Rule;

use PHPUnit\Framework\TestCase;
use Rezouce\Validator\Rule\Rule;
use Rezouce\Validator\Rule\RuleStack;
use Rezouce\Validator\Test\RegistryCreationTrait;

class RuleStackTest extends TestCase
{
    use RegistryCreationTrait;

    /** @test */
    public function itCanPerformValidationOfValidData()
    {
        $ruleStack = new RuleStack('email', [
            new Rule('required'), new Rule('email')
        ]);

        $validation = $ruleStack->validate([
            'email' => 'contact@rezouce.net'
        ], $this->createRegistry());

        $this->assertTrue($validation->isValid());
        $this->assertEmpty($validation->getErrorMessages());
    }

    /** @test */
    public function itCanPerformValidationOfInvalidData()
    {
        $ruleStack = new RuleStack('email', [
            new Rule('required'), new Rule('email')
        ]);

        $validation = $ruleStack->validate([
            'email' => 'test'
        ], $this->createRegistry());

        $this->assertFalse($validation->isValid());
        $this->assertEquals([
            'email' => ['This field should be a valid email.'],
        ], $validation->getErrorMessages());
    }

    /** @test */
    public function itValidatesTheRulesOnlyWhenNecessary()
    {
        $ruleStack = new RuleStack('email', [new Rule('email')]);

        $validation = $ruleStack->validate([], $this->createRegistry());

        $this->assertTrue($validation->isValid());
        $this->assertEmpty($validation->getErrorMessages());
    }
}
