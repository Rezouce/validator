<?php

namespace Rezouce\Validator\Test\Rule;

use PHPUnit\Framework\TestCase;
use Rezouce\Validator\Rule\Rule;
use Rezouce\Validator\Rule\RuleParser;

class RuleParserTest extends TestCase
{
    /** @test */
    public function it_parses_a_string_rule_to_a_rule_object()
    {
        $parser = new RuleParser();

        $rule = $parser->parse('in:jedi,sith');

        $this->assertInstanceOf(Rule::class, $rule);
        $this->assertEquals('in', $rule->getName());
        $this->assertEquals([['jedi', 'sith']], $rule->getOptions());
    }

    /** @test */
    public function it_parses_a_string_rule_to_a_rule_object_even_if_there_is_no_options()
    {
        $parser = new RuleParser();

        $rule = $parser->parse('required');

        $this->assertInstanceOf(Rule::class, $rule);
        $this->assertEquals('required', $rule->getName());
        $this->assertEmpty($rule->getOptions());
    }

    /** @test */
    public function it_parses_the_options_as_string_when_there_are_only_one_choice()
    {
        $parser = new RuleParser();

        $rule = $parser->parse('between:a:g');

        $this->assertEquals(['a', 'g'], $rule->getOptions());
    }

    /** @test */
    public function it_parses_an_array_rule_to_a_rule_object()
    {
        $parser = new RuleParser();

        $rule = $parser->parse(['in', ['jedi', 'sith']]);

        $this->assertInstanceOf(Rule::class, $rule);
        $this->assertEquals('in', $rule->getName());
        $this->assertEquals([['jedi', 'sith']], $rule->getOptions());
    }

    /** @test */
    public function it_parses_an_array_rule_to_a_rule_object_even_if_there_is_no_options()
    {
        $parser = new RuleParser();

        $rule = $parser->parse(['required']);

        $this->assertInstanceOf(Rule::class, $rule);
        $this->assertEquals('required', $rule->getName());
        $this->assertEmpty($rule->getOptions());
    }

    /** @test */
    public function it_returns_the_same_rule_object_as_provided()
    {
        $parser = new RuleParser();

        $rule = $parser->parse(new Rule('in', [['jedi', 'sith']]));

        $this->assertInstanceOf(Rule::class, $rule);
        $this->assertEquals('in', $rule->getName());
        $this->assertEquals([['jedi', 'sith']], $rule->getOptions());
    }

    /** @test */
    public function it_recasts_numeric_values_in_string_for_options()
    {
        $parser = new RuleParser();

        $rule = $parser->parse('min:12.5');

        $this->assertEquals([12.5], $rule->getOptions());
    }
}
