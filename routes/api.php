<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/users', [UserController::class, 'store']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Rota de teste "Olá, Mundo!"
Route::get('/hello', function () {
    return response()->json([
        'app_name' => 'KeyKeep API',
        'version' => '1.4.0',
        'status' => 'Online e Operante',
        'message' => 'Olá, Mundo! O backend em Laravel 12 está pronto para o React.'
    ], 200);
});