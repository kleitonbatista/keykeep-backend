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
       $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // 1. Valida se o usuário existe e a senha está correta
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Credenciais inválidas. Verifique seu e-mail e senha.'
            ], 401);
        }

        // 2. Valida se o e-mail já foi verificado no banco
        if (! $user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Seu e-mail ainda não foi verificado. Por favor, acesse sua caixa de entrada para confirmar o seu cadastro antes de fazer login.'
            ], 403);
        }

        // 3. Gera o token de acesso Sanctum se o e-mail estiver confirmado
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Login realizado com sucesso!',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user
        ], 200);
    
    }
}
