<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetUpdateClientRequest extends FormRequest
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
        $id = $this->segment(2);
        return [
            'type' => 'required|string|max:255',
            'company' => 'nullable:true|regex:/^\d+$/',
            'image' => 'nullable|image|mimes:jpeg,png,svg,webp,gif|max:1072',
            'name' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'register' => "nullable|unique:clients,register,{$id},id|regex:/^\d+$/",
            'name_company' => 'nullable|string|max:255',
            'full_name_company' => 'nullable|string|max:255',
            'cnpj' => "nullable|unique:clients,register,{$id},id|regex:/^\d+$/",
            'state_registration' => 'nullable|string',
            'email' => 'required|email|max:255|unique:clients',
            'contact' => 'nullable:true|regex:/^\d+$/',
            'whatsapp' => 'nullable:true|regex:/^\d+$/',
            'status' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'adress' => 'nullable|string|max:255',
            'postalcode' => 'nullable:true|regex:/^\d+$/',
            'token' => 'nullable|string',
            'billing' => 'nullable|string|max:255',
            'issued_date' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'O campo "tipo" é obrigatório.',
            'type.string' => 'O campo "tipo" deve ser um texto.',
            'type.max' => 'O campo "tipo" não pode ter mais de 255 caracteres.',

            'company.regex' => 'O campo "empresa" deve conter apenas números.',

            'image.image' => 'O campo "imagem" deve ser um arquivo de imagem válido.',
            'image.mimes' => 'O campo "imagem" deve ser um arquivo do tipo: jpeg, png, svg, webp ou gif.',
            'image.max' => 'O campo "imagem" não pode ser maior que 1072KB.',

            'name.string' => 'O campo "nome" deve ser um texto.',
            'name.max' => 'O campo "nome" não pode ter mais de 255 caracteres.',

            'lastname.string' => 'O campo "sobrenome" deve ser um texto.',
            'lastname.max' => 'O campo "sobrenome" não pode ter mais de 255 caracteres.',

            'register.string' => 'O campo "registro" deve ser um texto.',
            'register.max' => 'O campo "registro" não pode ter mais de 255 caracteres.',

            'name_company.string' => 'O campo "nome da empresa" deve ser um texto.',
            'name_company.max' => 'O campo "nome da empresa" não pode ter mais de 255 caracteres.',

            'full_name_company.string' => 'O campo "nome completo da empresa" deve ser um texto.',
            'full_name_company.max' => 'O campo "nome completo da empresa" não pode ter mais de 255 caracteres.',

            'cnpj.string' => 'O campo "CNPJ" deve ser um texto.',
            'cnpj.max' => 'O campo "CNPJ" não pode ter mais de 14 caracteres.',

            'state_registration.string' => 'O campo "inscrição estadual" deve ser um texto.',

            'email.required' => 'O campo "e-mail" é obrigatório.',
            'email.email' => 'O campo "e-mail" deve ser um endereço de e-mail válido.',
            'email.max' => 'O campo "e-mail" não pode ter mais de 255 caracteres.',
            'email.unique' => 'O campo "e-mail" já está cadastrado.',

            'contact.regex' => 'O campo "contato" deve conter apenas números.',

            'whatsapp.regex' => 'O campo "WhatsApp" deve conter apenas números.',

            'status.string' => 'O campo "status" deve ser um texto.',
            'status.max' => 'O campo "status" não pode ter mais de 255 caracteres.',

            'country.string' => 'O campo "país" deve ser um texto.',
            'country.max' => 'O campo "país" não pode ter mais de 255 caracteres.',

            'state.string' => 'O campo "estado" deve ser um texto.',
            'state.max' => 'O campo "estado" não pode ter mais de 255 caracteres.',

            'city.string' => 'O campo "cidade" deve ser um texto.',
            'city.max' => 'O campo "cidade" não pode ter mais de 255 caracteres.',

            'neighborhood.string' => 'O campo "bairro" deve ser um texto.',
            'neighborhood.max' => 'O campo "bairro" não pode ter mais de 255 caracteres.',

            'adress.string' => 'O campo "endereço" deve ser um texto.',
            'adress.max' => 'O campo "endereço" não pode ter mais de 255 caracteres.',

            'postalcode.regex' => 'O campo "código postal" deve conter apenas números.',

            'token.string' => 'O campo "token" deve ser um texto.',

            'billing.string' => 'O campo "cobrança" deve ser um texto.',
            'billing.max' => 'O campo "cobrança" não pode ter mais de 255 caracteres.',

            'issued_date.date' => 'O campo "data de emissão" deve ser uma data válida.',
        ];
    }

}
