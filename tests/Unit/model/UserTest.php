<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user creation.
     */
    public function test_user_creation()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('johndoe@example.com', $user->email);
    }

    /**
     * Test hidden attributes.
     */
    public function test_hidden_attributes()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);
        $array = $user->toArray();
        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('created_at', $array);
        $this->assertArrayNotHasKey('updated_at', $array);
        $this->assertArrayNotHasKey('deleted_at', $array);
    }

    /**
     * Test password casting.
     */
    public function test_password_casting()
    {
        $user = User::factory()->create([
            'password' => 'plain-text-password',
        ]);
        $this->assertNotEquals('plain-text-password', $user->password);
        $this->assertTrue(\Hash::check('plain-text-password', $user->password));
    }
}
