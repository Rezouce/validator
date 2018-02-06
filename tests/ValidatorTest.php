<?php

namespace Rezouce\Validator\Test;

use PHPUnit\Framework\TestCase;
use Rezouce\Validator\Validator\RespectValidator\RespectValidationContainer;
use Rezouce\Validator\Validator;

class ValidatorTest extends TestCase
{
    /** @test */
    public function it_can_perform_validation_of_valid_data()
    {
        $rules = [
            'username' => 'alpha',
            'email' => 'email',
            'age' => 'intval|between:7:77',
            'favorite_color' => 'in:red,green,blue,pink,yellow',
        ];

        $validator = new Validator($rules, new RespectValidationContainer);
        $validation = $validator->validate([
            'username' => 'Rezouce',
            'email' => 'contact@rezouce.net',
            'age' => 77,
        ]);

        $this->assertTrue($validation->isValid());
        $this->assertEmpty($validation->getErrorMessages());
        $this->assertEquals([
            'username' => 'Rezouce',
            'email' => 'contact@rezouce.net',
            'age' => 77,
            'favorite_color' => null,
        ], $validation->getData());
    }

    /** @test */
    public function it_can_perform_validation_of_invalid_data()
    {
        $rules = [
            'username' => 'alpha',
            'email' => 'email',
            'age' => 'intval|between:7:77',
            'favorite_color' => 'in:red,green,blue,pink,yellow',
        ];

        $validator = new Validator($rules, new RespectValidationContainer);
        $validation = $validator->validate([
            'username' => 'Rezouce',
            'email' => 'invalid email',
            'age' => 80,
            'favorite_color' => 'black',
        ]);

        $this->assertFalse($validation->isValid());
        $this->assertEquals([
            'email' => ['"invalid email" must be valid email'],
            'age' => ['80 must be less than or equal to 77'],
            'favorite_color' => ['"black" must be in { "red", "green", "blue", "pink", "yellow" }'],
        ], $validation->getErrorMessages());
        $this->assertEquals(['username' => 'Rezouce'], $validation->getData());
    }
}
