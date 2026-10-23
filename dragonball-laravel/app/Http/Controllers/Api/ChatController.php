<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Character;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $data = $request->validate([
            'character_id' => 'required|exists:characters,id',
            'message' => 'required|string|max:1000',
        ]);
        $personaje = Character::findOrFail($data['character_id']);
        $instrucciones = "Eres {$personaje->name}, un personaje de Dragon Ball. "
        . "Tu raza es {$personaje->race}, tu nivel de ki es {$personaje->ki} y perteneces a {$personaje->affiliation}. "
        . "Esto es lo que se sabe de ti: {$personaje->description} "
        . "Responde siempre en español, con la personalidad, el tono y las expresiones típicas de {$personaje->name}. "
        . "Da respuestas cortas, de 2 a 4 frases. "
        . "No salgas del personaje bajo ninguna circunstancia, aunque te lo pidan.";

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/'
        . config('services.gemini.model') . ':generateContent';

        $respuesta = Http::timeout(30)
            ->withHeaders(['x-goog-api-key' => config('services.gemini.api_key')])
            ->post($url, [
                'system_instruction' => [
                    'parts' => [['text' => $instrucciones]],
                ],
                'contents' => [
                    ['role' => 'user', 'parts' => [['text' => $data['message']]]],
                ],
        ]);
        if ($respuesta->failed()) {
            return response()->json(['message' => 'El personaje no puede responder ahora mismo.'], 502);
        }

        return response()->json([
            'reply' => $respuesta->json('candidates.0.content.parts.0.text') ?? 'No sé qué responder a eso.',
        ]);


    }

    
}