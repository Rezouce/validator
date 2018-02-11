<?php

namespace Rezouce\Validator\Test\Rule;

use PHPUnit\Framework\TestCase;
use Rezouce\Validator\Rule\DataParser;

class DataParserTest extends TestCase
{
    /** @test */
    public function it_can_parse_simple_rule_name()
    {
        $data = [
            'test' => 'data',
        ];

        $parser = new DataParser();

        $dataCollection = $parser->parse($data, 'test');

        $this->assertCount(1, $dataCollection);
        $this->assertEquals('data', $dataCollection->get(0)->getData());
        $this->assertEquals('test', $dataCollection->get(0)->getKey());
    }

    /** @test */
    public function it_can_parse_simple_star_rule_name()
    {
        $data = [
            'test1' => 'data1',
            'test2' => 'data2',
        ];

        $parser = new DataParser();

        $dataCollection = $parser->parse($data, '*');

        $this->assertCount(2, $dataCollection);
        $this->assertEquals('data1', $dataCollection->get(0)->getData());
        $this->assertEquals('test1', $dataCollection->get(0)->getKey());
        $this->assertEquals('data2', $dataCollection->get(1)->getData());
        $this->assertEquals('test2', $dataCollection->get(1)->getKey());
    }

    /** @test */
    public function it_can_parse_nested_rule_name()
    {
        $data = [
            'test' => [
                'name' => 'data1',
                'other' => 'data2',
            ],
        ];

        $parser = new DataParser();

        $dataCollection = $parser->parse($data, 'test.name');

        $this->assertCount(1, $dataCollection);
        $this->assertEquals('data1', $dataCollection->get(0)->getData());
        $this->assertEquals('test.name', $dataCollection->get(0)->getKey());
    }

    /** @test */
    public function it_can_parse_nested_star_rule_name()
    {
        $data = [
            'test' => ['data1', 'data2'],
        ];

        $parser = new DataParser();

        $dataCollection = $parser->parse($data, 'test.*');

        $this->assertCount(2, $dataCollection);
        $this->assertEquals('data1', $dataCollection->get(0)->getData());
        $this->assertEquals('test.0', $dataCollection->get(0)->getKey());
        $this->assertEquals('data2', $dataCollection->get(1)->getData());
        $this->assertEquals('test.1', $dataCollection->get(1)->getKey());
    }

    /** @test */
    public function it_can_parse_complex_rule_name()
    {
        $data = [
            'test' => [
                [
                    'name' => [
                        'foo' => 'data1',
                        'bar' => 'data2',
                    ],
                    'other' => 'data3',
                ],
                [
                    'name' => [
                        'foo' => 'data4',
                        'bar' => 'data5',
                        'baz' => 'data6',
                    ],
                    'other' => 'data7',
                ],
            ],
        ];

        $parser = new DataParser();

        $dataCollection = $parser->parse($data, 'test.*.name.foo');

        $this->assertCount(2, $dataCollection);
        $this->assertEquals('data1', $dataCollection->get(0)->getData());
        $this->assertEquals('test.0.name.foo', $dataCollection->get(0)->getKey());
        $this->assertEquals('data4', $dataCollection->get(1)->getData());
        $this->assertEquals('test.1.name.foo', $dataCollection->get(1)->getKey());
    }

    /** @test */
    public function it_return_null_value_when_a_rule_is_partially_matched()
    {
        $data = [
            'test' => [
                'test1' => [
                    'name' => [
                        'foo' => 'data1',
                        'bar' => 'data2',
                    ],
                    'other' => 'data3',
                ],
                'test2' => [
                    'other' => 'data5',
                ],
                'test3' => 'data4',
            ],
        ];

        $parser = new DataParser();

        $dataCollection = $parser->parse($data, 'test.*.name.foo');

        $this->assertCount(3, $dataCollection);
        $this->assertEquals('data1', $dataCollection->get(0)->getData());
        $this->assertEquals('test.test1.name.foo', $dataCollection->get(0)->getKey());
        $this->assertEquals(null, $dataCollection->get(1)->getData());
        $this->assertEquals('test.test2.name.foo', $dataCollection->get(1)->getKey());
        $this->assertEquals(null, $dataCollection->get(2)->getData());
        $this->assertEquals('test.test3.name.foo', $dataCollection->get(2)->getKey());
    }

    /** @test */
    public function it_return_an_array_with_a_null_value_when_a_star_rule_is_not_matched()
    {
        $data = [];

        $parser = new DataParser();

        $dataCollection = $parser->parse($data, 'test.*');

        $this->assertCount(1, $dataCollection);
        $this->assertEquals(null, $dataCollection->get(0)->getData());
        $this->assertEquals('test.0', $dataCollection->get(0)->getKey());
    }
}
