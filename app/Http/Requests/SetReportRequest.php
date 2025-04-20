<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetReportRequest extends FormRequest
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
            'report_description'    => 'required|string|max:255',
            'status'                => 'required|boolean', // Correção aqui!
            'group_id'              => 'required|integer',
            'message'               => 'required|string',
            'scheduleType'          => 'required|string|max:255',
            'selectedTime'          => 'nullable|string|max:255',
            'selectedWeekday'       => 'nullable|string|max:255',
            'selectedDay'           => 'nullable|string|max:255',
            'selectedMonth'         => 'nullable|string|max:255',

            'query_name'   => 'required|string|max:255',
            'query'        => 'required|string|regex:/^\s*SELECT\b/i',
        ];
    }

    public function messages(): array
    {
        return [
            'report_description.required' => 'A descrição do relatório é obrigatória.',
            'report_description.string'   => 'A descrição deve ser um texto válido.',
            'report_description.max'      => 'A descrição não pode ter mais que 255 caracteres.',

            'status.required' => 'O status é obrigatório.',
            'status.boolean'  => 'O status deve ser verdadeiro ou falso.',

            'group_id.required' => 'O ID do grupo é obrigatório.',
            'group_id.integer'  => 'O ID do grupo deve ser um número inteiro.',

            'message.required' => 'A mensagem é obrigatória.',
            'message.string'   => 'A mensagem deve ser um texto válido.',

            'scheduleType.required' => 'O tipo de agendamento é obrigatório.',
            'scheduleType.string'   => 'O tipo de agendamento deve ser um texto válido.',
            'scheduleType.max'      => 'O tipo de agendamento não pode ter mais que 255 caracteres.',

            'selectedTime.string'  => 'O horário selecionado deve ser um texto válido.',
            'selectedTime.max'     => 'O horário selecionado não pode ter mais que 255 caracteres.',

            'selectedWeekday.string'  => 'O dia da semana selecionado deve ser um texto válido.',
            'selectedWeekday.max'     => 'O dia da semana não pode ter mais que 255 caracteres.',

            'selectedDay.string'  => 'O dia selecionado deve ser um texto válido.',
            'selectedDay.max'     => 'O dia selecionado não pode ter mais que 255 caracteres.',

            'selectedMonth.string'  => 'O mês selecionado deve ser um texto válido.',
            'selectedMonth.max'     => 'O mês selecionado não pode ter mais que 255 caracteres.',

            'report_token.required'  => 'O token do relatório é obrigatório.',
            'report_token.unique'    => 'Este token de relatório já foi utilizado.',
            'report_token.string'    => 'O token do relatório deve ser um texto válido.',

            'user_id.required' => 'O ID do usuário é obrigatório.',
            'user_id.integer'  => 'O ID do usuário deve ser um número inteiro.',

            'query_name.required' => 'O nome da consulta é obrigatório.',
            'query_name.string'   => 'O nome da consulta deve ser um texto válido.',
            'query_name.max'      => 'O nome da consulta não pode ter mais que 255 caracteres.',

            'query.required' => 'A consulta é obrigatória.',
            'query.string'   => 'A consulta deve ser um texto válido.',
            'query.regex' => 'Somente SELECTS serão permitidos.',

            'query_token.required'  => 'O token da consulta é obrigatório.',
            'query_token.unique'    => 'Este token de consulta já foi utilizado.',
            'query_token.string'    => 'O token da consulta deve ser um texto válido.',

            'report_id.required' => 'O ID do relatório é obrigatório.',
            'report_id.integer'  => 'O ID do relatório deve ser um número inteiro.',
        ];
    }
}
