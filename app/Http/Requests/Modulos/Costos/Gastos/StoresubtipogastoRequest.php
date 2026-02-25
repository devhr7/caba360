<?php

namespace App\Http\Requests\Modulos\Costos\Gastos;

use Illuminate\Foundation\Http\FormRequest;

class StoresubtipogastoRequest extends FormRequest
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
    public function rules()
    {
        return [
            
            'gasto' => 'required',
            'tipogasto' => 'required',
            'codigo' => 'required|string|unique:subtipogastos,codigo',
            'subtipogasto' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El campo ID es obligatorio.',
            'gasto.required' => 'El campo Gasto es obligatorio.',
            'tipogasto.required' => 'El campo Tipo de Gasto es obligatorio.',
            'codigo.required' => 'El campo Código es obligatorio.',
            'codigo.unique' => 'El campo Código debe ser único.',
            'subtipogasto.required' => 'El campo Subtipo de Gasto es obligatorio.',
        ];
    }
}
