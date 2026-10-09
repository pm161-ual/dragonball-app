<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
    path: '/api/login',
    summary: 'Iniciar sesión y obtener un token',
    tags: ['Autenticación'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', type: 'string', example: 'admin@example.com'),
                new OA\Property(property: 'password', type: 'string', example: 'password'),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Login correcto: devuelve el usuario y el token'),
        new OA\Response(response: 401, description: 'Credenciales incorrectas'),
        new OA\Response(response: 422, description: 'Faltan el email o la contraseña'),
    ]
)]

    public function login(Request $request){
        // Valida los datos de entrada
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        // Busca el usuario por email 
        $user = User::where('email', $data['email'])->first();

        // Si no encuentra el usuario o la contraseña es incorrecta, devuelve un error 401
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Crea un token de acceso para el usuario si la autenticación es correcta
        $token = $user->createToken('api_token')->plainTextToken;

        // Devuelve el usuario y el token de acceso
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }


    #[OA\Post(
    path: '/api/logout',
    summary: 'Cerrar sesión (revoca el token actual)',
    tags: ['Autenticación'],
    security: [['sanctum' => []]],
    responses: [
        new OA\Response(response: 204, description: 'Sesión cerrada'),
        new OA\Response(response: 401, description: 'No autenticado'),
    ]
)]

    //Cuando el usuario hace logout, se eliminan todos los tokens de acceso del usuario
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }

}
