<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanLoginWithCorrectCredentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure(['token']);
    }

    public function testUserCannotLoginWithIncorrectCredentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'incorrect@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(401);
    }
}
