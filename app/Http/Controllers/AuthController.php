<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

       return response()->json([
            'user' => $user
        ], 200);
    }

    /**
     * Revoga o token atual e encerra a sessão.
     */
    public function logout(Request $request): JsonResponse
    {
        // Apaga apenas o token que fez a requisição atual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso!'
        ], 200);
    }
    
   public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        // Buscar usuário pelo e-mail
        $user = User::where('email', $credentials['email'])->first();

        // Verificar existência do usuário e validar o hash da senha
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return Response()->json([
                'message' => 'Credenciais inválidas. Verifique seu e-mail e senha.'
            ], 401);
        }

        // Criar o token de acesso do Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Login realizado com sucesso!',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ]
        ], 200);
    }
}
