<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/users', [UserController::class, 'store']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Endpoints do Cofre de Credenciais
    Route::get('/credentials', [CredentialController::class, 'index']);
    Route::post('/credentials', [CredentialController::class, 'store']);
    Route::get('/credentials/{id}', [CredentialController::class, 'show']);
    Route::put('/credentials/{id}', [CredentialController::class, 'update']);
    Route::delete('/credentials/{id}', [CredentialController::class, 'destroy']); 

});
Route::get('/categories',[CategoryController::class,'index']);

// Rota de teste "Olá, Mundo!"
Route::get('/hello', function () {
    return response()->json([
        'app_name' => 'KeyKeep API',
        'version' => '1.4.0',
        'status' => 'Online e Operante',
        'message' => 'Olá, Mundo! O backend em Laravel 12 está pronto para o React.'
    ], 200);
});