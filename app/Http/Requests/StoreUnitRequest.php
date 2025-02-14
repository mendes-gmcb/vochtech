<?php

namespace App\Http\Requests;

use App\Rules\Cnpj;
use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
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
            'trade_name' => 'required|string|max:255',
            'legal_name' => 'required|string|max:255',
            'cnpj' => ['required', 'string', 'size:14', 'regex:/^\d{14}$/', 'unique:units,cnpj,' . $this->unit?->id, new Cnpj],
            'brand_id' => 'required|integer|exists:brands,id'
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('cnpj')) {
            // Remove caracteres especiais do CNPJ antes da validação
            $this->merge([
                'cnpj' => preg_replace('/[^0-9]/', '', $this->cnpj)
            ]);
        }
    }
}
