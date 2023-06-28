<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_return_token_with_correct_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function test_login_return_error_with_invalid_credentials()
    {
        $response = $this->postJson(route('login'), [
            'email' => 'sohrab@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
    }
}
