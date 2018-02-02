<?php

namespace Rezouce\Validator\Test\Rule;

use PHPUnit\Framework\TestCase;
use Rezouce\Validator\Rule\Rule;
use Rezouce\Validator\Rule\RuleParser;

class RuleParserTest extends TestCase
{
    /** @test */
    public function itParsesAStringRuleToARuleObject()
    {
        $parser = new RuleParser();

        $rule = $parser->parse('in:jedi,sith');

        $this->assertInstanceOf(Rule::class, $rule);
        $this->assertEquals('in', $rule->getName());
        $this->assertEquals(['jedi', 'sith'], $rule->getOptions());
    }

    /** @test */
    public function itParsesAStringRuleToARuleObjectEvenIfThereIsNoOptions()
    {
        $parser = new RuleParser();

        $rule = $parser->parse('required');

        $this->assertInstanceOf(Rule::class, $rule);
        $this->assertEquals('required', $rule->getName());
        $this->assertEmpty($rule->getOptions());
    }

    /** @test */
    public function itParsesArrayRuleToARuleObject()
    {
        $parser = new RuleParser();

        $rule = $parser->parse(['in', ['jedi', 'sith']]);

        $this->assertInstanceOf(Rule::class, $rule);
        $this->assertEquals('in', $rule->getName());
        $this->assertEquals(['jedi', 'sith'], $rule->getOptions());
    }

    /** @test */
    public function itParsesArrayRuleToARuleObjectEvenIfThereIsNoOptions()
    {
        $parser = new RuleParser();

        $rule = $parser->parse(['required']);

        $this->assertInstanceOf(Rule::class, $rule);
        $this->assertEquals('required', $rule->getName());
        $this->assertEmpty($rule->getOptions());
    }

    /** @test */
    public function itReturnsTheAlreadyDefinedRule()
    {
        $parser = new RuleParser();

        $rule = $parser->parse(new Rule('in', ['jedi', 'sith']));

        $this->assertInstanceOf(Rule::class, $rule);
        $this->assertEquals('in', $rule->getName());
        $this->assertEquals(['jedi', 'sith'], $rule->getOptions());
    }
}
