<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    public function test_login_returns_token_with_valid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(
            '/api/v1/auth/login',
            [
                'email' => $user->email,
                'password' => 'password'
            ]
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token']);
    }
    public function test_login_returns_error_with_invalid_credentials(): void
    {
        $response = $this->postJson(
            '/api/v1/auth/login',
            [
                'email' => 'nonexistent@user.com',
                'password' => 'password'
            ]
        );

        $response->assertStatus(422);
    }
}
