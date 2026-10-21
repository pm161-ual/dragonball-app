<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_correcto_devuelve_token(): void
    {
        User::factory()->create(['email' => 'goku@test.com']);

        $response = $this->postJson('/api/login', [
            'email' => 'goku@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token', 'user']);
    }

    public function test_login_con_contrasena_incorrecta_devuelve_401(): void
    {
        User::factory()->create(['email' => 'goku@test.com']);

        $response = $this->postJson('/api/login', [
            'email' => 'goku@test.com',
            'password' => 'kamehameha',
        ]);

        $response->assertStatus(401);
    }

    public function test_logout_borra_el_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(204);
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
        public function test_registro_crea_usuario_y_envia_email(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/register', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);

        $user = User::where('email', 'test@example.com')->first();
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_login_sin_verificar_devuelve_403(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(403);
    }

}
