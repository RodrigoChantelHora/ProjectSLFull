<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetContactRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'status'      => 'required|boolean',
            'email'       => 'nullable|string|max:255|email',
            'whatsapp'    => 'nullable|regex:/^\d+$/',
            'group_id'    => 'nullable|numeric|max:255',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'O nome é obrigatório.',
            'name.string'          => 'O nome deve ser uma string.',
            'name.max'             => 'O nome não pode ter mais que 255 caracteres.',
            'status.required'      => 'O status é obrigatório.',
            'status.boolean'       => 'O status deve ser um valor booleano.',
            'email.string'         => 'O email deve ser uma string.',
            'email.max'            => 'O email não pode ter mais que 255 caracteres.',
            'email.email'          => 'O email deve ser um endereço válido.',
            'whatsapp.regex'       => 'O WhatsApp deve conter apenas números.',
            'group_id.numeric'     => 'O ID do grupo deve ser um número.',
            'group_id.max'         => 'O ID do grupo não pode ter mais que 255 caracteres.',
            'description.string'   => 'A descrição deve ser uma string.',
            'description.max'      => 'A descrição não pode ter mais que 255 caracteres.',
        ];
    }
}
