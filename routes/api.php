<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rota de teste "Olá, Mundo!"
Route::get('/hello', function () {
    return response()->json([
        'app_name' => 'KeyKeep API',
        'version' => '1.4.0',
        'status' => 'Online e Operante',
        'message' => 'Olá, Mundo! O backend em Laravel 12 está pronto para o React.'
    ], 200);
});