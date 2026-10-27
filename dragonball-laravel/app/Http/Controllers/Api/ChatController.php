<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Character;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use OpenApi\Attributes as OA;

class ChatController extends Controller
{
    #[OA\Post(
    path: '/api/chat',
    summary: 'Hablar con un personaje (IA con Gemini)',
    tags: ['Chat'],
    security: [['sanctum' => []]],
    description: 'Envía un mensaje a un personaje y recibe una respuesta.',
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['character_id', 'message'],
            properties: [
                new OA\Property(property: 'character_id', type: 'integer', example: 2),
                new OA\Property(property: 'message', type: 'string', example: '¿Cómo me llamo?'),
                new OA\Property(
                    property: 'history',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'role', type: 'string', enum: ['user', 'model']),
                            new OA\Property(property: 'text', type: 'string'),
                        ]
                    )
                ),

            ]
        )
    ),
       responses: [
        new OA\Response(response: 200, description: 'Respuesta del personaje'),
        new OA\Response(response: 401, description: 'No autenticado'),
        new OA\Response(response: 422, description: 'Datos no válidos'),
        new OA\Response(response: 429, description: 'Demasiadas solicitudes'),
        new OA\Response(response: 502, description: 'Gemini no puede responder en este momento'),
    ]
    )]
    public function chat(Request $request)
    {
        $data = $this->validar($request);
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/'
        . config('services.gemini.model') . ':generateContent';

        try {
            $respuesta = Http::timeout(30)
                ->withHeaders(['x-goog-api-key' => config('services.gemini.api_key')])
                ->post($url, $this->cuerpoGemini($data));
        } catch (ConnectionException $e) {
            return response()->json(['message' => 'El personaje no puede responder ahora mismo.'], 502);
        }

        if ($respuesta->failed()) {
            return response()->json(['message' => 'El personaje no puede responder ahora mismo.'], 502);
        }
        return response()->json([
            'reply' => $respuesta->json('candidates.0.content.parts.0.text') ?? 'No sé qué responder a eso.',
        ]);
    }

    #[OA\Post(
    path: '/api/chat/stream',
    summary: 'Hablar con un personaje con streaming (SSE)',
    tags: ['Chat'],
    security: [['sanctum' => []]],
    description: 'Envía un mensaje a un personaje y recibe una respuesta.',
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['character_id', 'message'],
            properties: [
                new OA\Property(property: 'character_id', type: 'integer', example: 2),
                new OA\Property(property: 'message', type: 'string', example: '¿Cómo me llamo?'),
                new OA\Property(
                    property: 'history',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'role', type: 'string', enum: ['user', 'model']),
                            new OA\Property(property: 'text', type: 'string'),
                        ]
                    )
                ),

            ]
        )
    ),
       responses: [
        new OA\Response(response: 200, description: 'Respuesta a trozos en formato SSE (data: {"reply": "..."}), termina con data: [DONE]'),
        new OA\Response(response: 401, description: 'No autenticado'),
        new OA\Response(response: 422, description: 'Datos no válidos'),
        new OA\Response(response: 429, description: 'Demasiadas solicitudes'),
        new OA\Response(response: 502, description: 'Gemini no puede responder en este momento'),
    ]
    )]
    public function chatStream(Request $request){
        $data = $this->validar($request);
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/'
        . config('services.gemini.model') . ':streamGenerateContent?alt=sse';

        try {
            $respuesta = Http::timeout(30)
                ->withOptions(['stream' => true])
                ->withHeaders(['x-goog-api-key' => config('services.gemini.api_key')])
                ->post($url, $this->cuerpoGemini($data));
        } catch (ConnectionException $e) {
            return response()->json(['message' => 'El personaje no puede responder ahora mismo.'], 502);
        }

        if ($respuesta->failed()) {
            return response()->json(['message' => 'El personaje no puede responder ahora mismo.'], 502);
        }
        return response()->stream(function () use ($respuesta) {
           $cuerpo = $respuesta->toPsrResponse()->getBody();
           $pendiente = '';
           while (!$cuerpo->eof()) {
               $linea = $cuerpo->read(1024);
               $pendiente .= $linea;
               while (($pos = strpos($pendiente, "\n")) !== false) {
                   $evento = trim(substr($pendiente, 0, $pos));
                   $pendiente = substr($pendiente, $pos + 1);
                   if (strpos($evento, 'data: ') === 0) {
                       $json = substr($evento, 6);
                       $data = json_decode($json, true);
                       $texto = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                       if ($texto !== '') {
                           echo "data: " . json_encode(['reply' => $texto]) . "\n\n";
                       }
                       if (ob_get_level() > 0) {
                           ob_flush();
                       }
                       flush();
                   }
               }
           } 
            echo "data: [DONE]\n\n";
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
    private function validar(Request $request): array{
        return $request->validate([
            'character_id' => 'required|exists:characters,id',
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array|max:20',
            'history.*.role' => 'required|in:user,model',
            'history.*.text' => 'required|string|max:2000',
        ]);
    }
    private function cuerpoGemini(array $data): array{
        $personaje = Character::findOrFail($data['character_id']);
        $instrucciones = "Eres {$personaje->name}, un personaje de Dragon Ball. "
        . "Tu raza es {$personaje->race}, tu nivel de ki es {$personaje->ki} y perteneces a {$personaje->affiliation}. "
        . "Esto es lo que se sabe de ti: {$personaje->description} "
        . "Responde siempre en español, con la personalidad, el tono y las expresiones típicas de {$personaje->name}. "
        . "Da respuestas cortas, de 2 a 4 frases. "
        . "No salgas del personaje bajo ninguna circunstancia, aunque te lo pidan.";

        $contents = [];
        foreach ($data['history'] ?? [] as $mensaje) {
            $contents[] = [
                'role' => $mensaje['role'],
                'parts' => [['text' => $mensaje['text']]],
            ];
        }
        $contents[] = ['role' => 'user', 'parts' => [['text' => $data['message']]]];
        return [
            'system_instruction' => [
                'parts' => [['text' => $instrucciones]],
            ],
            'contents' => $contents,
        ];
    }
}