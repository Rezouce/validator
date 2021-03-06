<?php

namespace Rezouce\Validator\Test\Rule;

use PHPUnit\Framework\TestCase;
use Rezouce\Validator\Rule\Rule;
use Rezouce\Validator\Rule\RulesParser;

class RulesParserTest extends TestCase
{
    /** @test */
    public function it_parses_string_rules_to_rule_objects()
    {
        $parser = new RulesParser();

        $parsedRules = $parser->parse('required|in:jedi,sith');

        $this->assertCount(2, $parsedRules);
        $this->assertArrayOf(Rule::class, $parsedRules);

        $rule1 = current($parsedRules);
        $this->assertEquals('required', $rule1->getName());
        $this->assertEmpty($rule1->getOptions());

        $rule2 = next($parsedRules);
        $this->assertEquals('in', $rule2->getName());
        $this->assertEquals([['jedi', 'sith']], $rule2->getOptions());
    }

    /** @test */
    public function it_parses_array_of_string_rules_to_rule_objects()
    {
        $parser = new RulesParser();

        $parsedRules = $parser->parse(['required', 'in:jedi,sith']);

        $this->assertCount(2, $parsedRules);
        $this->assertArrayOf(Rule::class, $parsedRules);

        $rule1 = current($parsedRules);
        $this->assertEquals('required', $rule1->getName());
        $this->assertEmpty($rule1->getOptions());

        $rule2 = next($parsedRules);
        $this->assertEquals('in', $rule2->getName());
        $this->assertEquals([['jedi', 'sith']], $rule2->getOptions());
    }

    /** @test */
    public function it_returns_the_same_rule_object_as_provided()
    {
        $parser = new RulesParser();

        $parsedRules = $parser->parse([new Rule('required'), new Rule('in', ['jedi', 'sith'])]);

        $this->assertCount(2, $parsedRules);
        $this->assertArrayOf(Rule::class, $parsedRules);

        $rule1 = current($parsedRules);
        $this->assertEquals('required', $rule1->getName());
        $this->assertEmpty($rule1->getOptions());

        $rule2 = next($parsedRules);
        $this->assertEquals('in', $rule2->getName());
        $this->assertEquals(['jedi', 'sith'], $rule2->getOptions());
    }

    private function assertArrayOf(string $expectedClassType, array $actualData)
    {
        foreach ($actualData as $data) {
            $this->assertInstanceOf($expectedClassType, $data);
        }
    }
}
