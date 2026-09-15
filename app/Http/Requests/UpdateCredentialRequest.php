<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCredentialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id'  => ['sometimes', 'required', 'exists:categories,id'],
            'account_name' => ['sometimes', 'required', 'string', 'max:255'],
            'login'        => ['sometimes', 'required', 'string', 'max:255'],
            'password'     => ['nullable', 'string', 'min:1'], // Opcional: só atualiza se for informada
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.exists'    => 'A categoria selecionada é inválida.',
            'account_name.required' => 'O nome da conta é obrigatório.',
            'account_name.max'      => 'O nome da conta não pode exceder 255 caracteres.',
            'login.required'        => 'O login/e-mail é obrigatório.',
        ];
    }
}
