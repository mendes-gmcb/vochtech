<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:employees,email,' . $this->employee?->id, 'max:255'],
            'cpf' => ['required', 'string', 'size:11', 'unique:employees,cpf,' . $this->employee?->id],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('cpf')) {
            // Remove caracteres especiais do CNPJ antes da validação
            $this->merge([
                'cpf' => preg_replace('/[^0-9]/', '', $this->cpf)
            ]);
        }
    }
}
