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
        new OA\Response(response: 403, description: 'El email no está verificado'),
        new OA\Response(response: 422, description: 'Faltan el email o la contraseña'),
    ]
)]

    public function login(Request $request){
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Email o contraseña incorrectos'], 401);
        }
        if (!$user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Debes verificar tu email antes de iniciar sesión'], 403);
        }
        $token = $user->createToken('api_token')->plainTextToken;

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

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }

     #[OA\Post(
    path: '/api/register',
    summary: 'Registrar un nuevo usuario',
    tags: ['Autenticación'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'email', 'password', 'password_confirmation'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Usuario'),  
                new OA\Property(property: 'email', type: 'string', example: 'admin@example.com'),
                new OA\Property(property: 'password', type: 'string', example: 'password'),
                new OA\Property(property: 'password_confirmation', type: 'string', example: 'password'),
            ]
        )
    ),
       responses: [
        new OA\Response(response: 201, description: 'Usuario registrado, se envía email de confirmación'),
        new OA\Response(response: 422, description: 'Datos no válidos o email ya registrado'),
    ]
)]
    public function register(Request $request){
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        $user->sendEmailVerificationNotification();
        return response()->json([
            'user' => $user,
            'message' => 'Usuario registrado. Revisa tu email para confirmar la cuenta.',
        ], 201);
    }



        #[OA\Get(
        path: '/api/email/verify/{id}/{hash}',
        summary: 'Verificar correo electrónico',
        tags: ['Autenticación'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'hash', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 302, description: 'Email confirmado, redigirge a la página de login'),
            new OA\Response(response: 403, description: 'Enlace no válido'),
        ]
    )]
    public function verify($id, $hash)
    {
        $user = User::findOrFail($id);

        if (sha1($user->email) !== $hash) {
            return response()->json(['message' => 'Enlace no válido'], 403);
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect(config('app.frontend_url') . '/login');
    }


}
