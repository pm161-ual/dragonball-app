<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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

    //Cuando el usuario hace logout, se eliminan todos los tokens de acceso del usuario
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }

}
