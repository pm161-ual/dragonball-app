<?php

namespace Tests\Feature;
use App\Models\Character;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;




class ChatTest extends TestCase
{

    use RefreshDatabase;
    public function test_chat_devuelve_respuesta_del_personaje(): void
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    ['content' => ['parts' => [['text' => '¡Insecto!']]]],
                ],
            ]),
        ]);

        Sanctum::actingAs(User::factory()->create());
        $personaje = Character::factory()->create();

        $response = $this->postJson('/api/chat', [
            'character_id' => $personaje->id,
            'message' => 'Hola',
        ]);
        $response->assertStatus(200);
        $response->assertJsonPath('reply', '¡Insecto!');
    }
    
    public function test_chat_sin_token_devuelve_401(): void
    {
        $personaje = Character::factory()->create();
        $response = $this->postJson('/api/chat', [
            'character_id' => $personaje->id,
            'message' => 'Hola',
        ]);
        $response->assertStatus(401);
    }
        public function test_chat_stream_devuelve_trozos(): void
    {
        $trozoGemini = json_encode([
            'candidates' => [
                ['content' => ['parts' => [['text' => 'Insecto']]]],
            ],
        ]);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response(
                "data: {$trozoGemini}\r\n\r\n",
                200,
                ['Content-Type' => 'text/event-stream']
            ),
        ]);

        Sanctum::actingAs(User::factory()->create());
        $personaje = Character::factory()->create();

        $response = $this->postJson('/api/chat/stream', [
            'character_id' => $personaje->id,
            'message' => 'Hola',
        ]);

        $response->assertStatus(200);

        $contenido = $response->streamedContent();
        $this->assertStringContainsString('"reply":"Insecto"', $contenido);
        $this->assertStringContainsString('[DONE]', $contenido);
    }

    
}
