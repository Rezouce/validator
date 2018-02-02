<?php

namespace Rezouce\Validator\Test;

use PHPUnit\Framework\TestCase;
use Rezouce\Validator\Validator;

class FeatureTest extends TestCase
{
    use RegistryCreationTrait;

    /** @test */
    public function itCanPerformValidationOfValidData()
    {
        $rules = [
            'username' => 'required',
            'email' => 'required|email',
            'favorite_color' => 'in:red,green,blue,pink,yellow',
        ];

        $validator = new Validator($rules, $this->createRegistry());
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

        $validator = new Validator($rules, $this->createRegistry());
        $validation = $validator->validate([
            'email' => 'invalid email',
            'favorite_color' => 'black',
        ]);

        $this->assertFalse($validation->isValid());
        $this->assertEquals([
            'username' => ['This field is required.'],
            'email' => ['This field should be a valid email.'],
            'favorite_color' => ['You must provide one of "red, green, blue, pink, yellow".'],
        ], $validation->getErrorMessages());
    }
}
