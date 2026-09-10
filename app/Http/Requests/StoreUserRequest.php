<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // Exige o campo password_confirmation
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'O campo nome é obrigatório.',
            'email.required'    => 'O campo e-mail é obrigatório.',
            'email.email'       => 'Informe um endereço de e-mail válido.',
            'email.unique'      => 'Este e-mail já está cadastrado no sistema.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min'      => 'A senha deve conter no mínimo 8 caracteres.',
            'password.confirmed'=> 'A confirmação de senha não confere.',
        ];
    }
}
