<?php

namespace Rezouce\Validator\Test;

use PHPUnit\Framework\TestCase;

class FeatureTest extends TestCase
{
    /** @test */
    public function itCanPerformValidationOfValidData()
    {
        $rules = [
            'username' => 'required',
            'email' => 'required|email',
            'favorite_color' => 'in:red,green,blue,pink,yellow',
        ];

        $validator = new Validator($rules);
        $validation = $validator->validate([
            'username' => 'Rezouce',
            'email' => 'contact@rezouce.net'
        ]);

        $this->assertTrue($validation->isValid());
        $this->assertEmpty($validation->getErrorMessages());
    }

    /** @test */
    public function itCanPerformValidationOfInvalidData()
    {
        $rules = [
            'username' => 'required',
            'email' => 'required|email',
            'favorite_color' => 'in:red,green,blue,pink,yellow',
        ];

        $validator = new Validator($rules);
        $validation = $validator->validate([
            'favorite_color' => 'black',
        ]);

        $this->assertFalse($validation->isValid());
        $this->assertEquals([
            'username' => ['This field is required.'],
            'email' => ['This field is required.'],
            'favorite_color' => ['You must provide one of "red, green, blue, pink, yellow", black provided.'],
        ], $validation->getErrorMessages());
    }
}
