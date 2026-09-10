<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(StoreUserRequest $request): JsonResponse
    {
        // Os dados já chegam validados pelo StoreUserRequest
        $validatedData = $request->validated();

        // Criptografar a senha antes de salvar
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Persistir o usuário no banco de dados
        $user = User::create($validatedData);
        //event(new Registered($user));

        return response()->json([
            'message' => 'Usuário cadastrado com sucesso!',
            'user'    => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'created_at' => $user->created_at,
            ]
        ], 201);
    }
}
