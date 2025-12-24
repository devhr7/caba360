<?php

namespace App\Http\Requests\Modulos\Cumplidos\CumplidoLaborCampo;

use Illuminate\Foundation\Http\FormRequest;

class Storecumplido_laborcampoRequest extends FormRequest
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
            'codigo' => 'required|unique:cump_laborcampos,codigo',
        ];
    }
}
