<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmailsGeraisController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/users', [UserController::class, 'store']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/resend-verification-email', [EmailsGeraisController::class, 'resendVerificationEmail']);


Route::middleware(['auth:sanctum','verified'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Endpoints do Cofre de Credenciais
    Route::get('/credentials', [CredentialController::class, 'index']);
    Route::post('/credentials', [CredentialController::class, 'store']);
    Route::get('/credentials/{id}', [CredentialController::class, 'show']);
    Route::put('/credentials/{id}', [CredentialController::class, 'update']);
    Route::delete('/credentials/{id}', [CredentialController::class, 'destroy']); 

});

// 1. Rota para solicitar o e-mail de redefinição
Route::post('/forgot-password', [EmailsGeraisController::class, 'solicitarRedefinicaoSenha']);

// 2. Rota assinada para processar a nova senha
Route::post('/reset-password/{id}', [EmailsGeraisController::class, 'redefinirSenha'])
    ->middleware(['signed'])
    ->name('password.reset');

// Rota para reenviar o e-mail de verificação
Route::middleware('auth:sanctum')->post('/email/verification-notification', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return response()->json(['message' => 'E-mail já verificado.'], 400);
    }

    $request->user()->sendEmailVerificationNotification();

    return response()->json(['message' => 'Link de verificação enviado!']);
});

// Rota de verificação do e-mail (acionada pelo botão)
Route::get('/email/verify/{id}/{hash}', [EmailsGeraisController::class, 'verificarEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::get('/categories',[CategoryController::class,'index']);
Route::get('/teste-email/{email}',[EmailsGeraisController::class,'enviarEmailTeste']);

// Rota de teste "Olá, Mundo!"
Route::get('/hello', function () {
    return response()->json([
        'app_name' => 'KeyKeep API',
        'version' => '1.4.0',
        'status' => 'Online e Operante',
        'message' => 'Olá, Mundo! O backend em Laravel 12 está pronto para o React.'
    ], 200);
});