<?php

namespace App\Http\Controllers;

use App\Mail\RedefinirSenhaNotification;
use App\Mail\TesteEmailNotification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class EmailsGeraisController extends Controller
{
    
/**
     * Envia um e-mail simples de teste para validar a integração com o Mailpit.
     */
    public function enviarEmailTeste(string $email): JsonResponse
    {
       /* Mail::raw('Este é um e-mail de teste disparado pelo KeyKeep via Mailpit!', function ($message) use ($email) {
            $message->to($email)
                    ->subject('Teste de Envio de E-mail - KeyKeep');
        });

        return response()->json([
            'message' => 'E-mail de teste enviado para o Mailpit com sucesso!'
        ], 200);*/

        Mail::to($email)->send(new TesteEmailNotification($email));

        return response()->json([
            'message' => "E-mail de teste estilizado enviado para {$email} com sucesso!"
        ], 200);
    }

    public function resendVerificationEmail(Request $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'E-mail já verificado.'
            ], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Link de verificação reenviado com sucesso!'
        ], 200);
    }
    /**
     * Confirma a verificação do e-mail do usuário através do link assinado.
     */
    public function verificarEmail(Request $request, int $id, string $hash): JsonResponse
    {
        $user = User::findOrFail($id);

        // Valida se o hash da URL corresponde ao e-mail do usuário
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'message' => 'Link de verificação inválido ou expirado.'
            ], 403);
        }

        // Marca a coluna email_verified_at com a data/hora atual no MySQL
        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return response()->json([
            'message' => 'E-mail verificado com sucesso! Seu cofre no KeyKeep está liberado.'
        ], 200);
    }

    public function solicitarRedefinicaoSenha(Request $request): JsonResponse
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->email)->first();

    // Link assinado válido por 15 minutos
    $url = URL::temporarySignedRoute(
        'password.reset',
        now()->addMinutes(15),
        ['id' => $user->id]
    );

    // Envia o e-mail customizado com o layout Blade
    Mail::to($user->email)->send(new RedefinirSenhaNotification($user, $url));

    return response()->json([
        'message' => 'Link de redefinição de senha enviado para o e-mail cadastrado.'
    ], 200);
}


/**
 * Processa a redefinição de senha do usuário.
 */
public function redefinirSenha(Request $request, int $id): JsonResponse
{
    // Valida se a URL assinada é válida e não expirou
    if (! $request->hasValidSignature()) {
        return response()->json([
            'message' => 'O link de redefinição de senha é inválido ou expirou.'
        ], 401);
    }

    $request->validate([
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::findOrFail($id);

    // Atualiza a nova senha criptografada
    $user->update([
        'password' => Hash::make($request->password)
    ]);

    // Opcional: revoga todos os tokens Sanctum do usuário
    $user->tokens()->delete();

    return response()->json([
        'message' => 'Senha alterada com sucesso! Você já pode realizar o login com a nova senha.'
    ], 200);
}
}
