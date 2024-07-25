<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the user login endpoint
     *
     * @return void
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('api/login', [
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'token'])
            ->assertJson(['message' => 'Login successful']);
    }

    /**
     * Test the user login endpoint with invalid credentials
     *
     * @return void
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password'),
        ]);
        
        $response = $this->postJson('api/login', [
            'email' => 'johndoe@example.com',
            'password' => 'wrongpassword',
        ]);
        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials']);
    }
    /**
     * Test the user registration endpoint
     *
     * @return void
     */
    public function test_user_can_register()
    {
        $response = $this->postJson('api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe123@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'User registered successfully']);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe123@example.com',
        ]);
    }

    /**
     * Test the user registration endpoint with invalid data
     *
     * @return void
     */
    public function test_user_cannot_register_with_invalid_data()
    {
        $response = $this->postJson('api/register', [
            'name' => 'John Doe',
            'email' => 'not-an-email',
            'password' => 'password',
        ]); 
        $response->assertStatus(403)
            ->assertJson(['message' => 'Validation error']);
    }
}
