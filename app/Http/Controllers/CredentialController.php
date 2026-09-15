<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCredentialRequest;
use App\Http\Requests\UpdateCredentialRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       /* $credentials = $request->user()->credentials()->with('category')->get();

        // Opcional: Descriptografar a senha na listagem ou deixar sob demanda
        $credentials->transform(function ($credential) {
            $credential->decrypted_password = Crypt::decryptString($credential->encrypted_password);
            return $credential;
        });

        return response()->json($credentials, 200);*/

        /**
 * Lista todas as credenciais pertencentes ao usuário logado.
 */
    // Busca apenas as credenciais do usuário logado, trazendo os dados da categoria vinculada
    $credentials = $request->user()->credentials()->with('category')->get();

    return response()->json($credentials, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCredentialRequest $request)
    {
        $validated = $request->validated();
        // O id do usuário é recuperado automaticamente do Token Bearer (Sanctum)
        $credential = $request->user()->credentials()->create([
            'category_id'        => $validated['category_id'],
            'account_name'       => $validated['account_name'],
            'login'              => $validated['login'],
            'encrypted_password' => Crypt::encryptString($validated['password']),
        ]);

        return response()->json([
            'message'    => 'Credencial salva com sucesso!',
            'credential' => $credential,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
   public function show(Request $request, string $id): JsonResponse
{
    try {
        // Busca a credencial pertencente ao usuário logado
        $credential = $request->user()->credentials()->with('category')->findOrFail($id);

        // Descriptografa a senha
        $credential->decrypted_password = Crypt::decryptString($credential->encrypted_password);

        return response()->json([
            'credential' => $credential,
        ], 200);

    } catch (ModelNotFoundException $e) {
        return response()->json([
            'message' => 'Credencial não encontrada ou você não tem permissão para acessá-la.'
        ], 404);
    }
}

   /**
 * Atualiza os dados de uma credencial existente.
 */
public function update(UpdateCredentialRequest $request, string $id): JsonResponse
{
    try {
        // Garante que a credencial pertence ao usuário logado
        $credential = $request->user()->credentials()->findOrFail($id);

        $validated = $request->validated();

        // Criptografa a nova senha caso ela tenha sido enviada no request
        if (!empty($validated['password'])) {
            $validated['encrypted_password'] = Crypt::encryptString($validated['password']);
        }

        // Remove a propriedade 'password' aberta para evitar erro de SQL no update
        unset($validated['password']);

        // Atualiza os registros no banco de dados
        $credential->update($validated);

        return response()->json([
            'message'    => 'Credencial atualizada com sucesso!',
            'credential' => $credential,
        ], 200);

    } catch (ModelNotFoundException $e) {
        return response()->json([
            'message' => 'Credencial não encontrada ou você não tem permissão para alterá-la.'
        ], 404);
    }
}

   /**
 * Remove uma credencial do cofre do usuário.
 */
public function destroy(Request $request, string $id): JsonResponse
{
    try {
        // Garante que o registro pertence exclusivamente ao usuário autenticado
        $credential = $request->user()->credentials()->findOrFail($id);

        // Deleta o registro do banco de dados
        $credential->delete();

        return response()->json([
            'message' => 'Credencial removida com sucesso!'
        ], 200);

    } catch (ModelNotFoundException $e) {
        return response()->json([
            'message' => 'Credencial não encontrada ou você não tem permissão para excluí-la.'
        ], 404);
    }
}
}
