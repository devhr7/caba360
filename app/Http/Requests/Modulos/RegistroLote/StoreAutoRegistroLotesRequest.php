<?php

namespace App\Http\Requests\Modulos\RegistroLote;

use Illuminate\Foundation\Http\FormRequest;

class StoreAutoRegistroLotesRequest extends FormRequest
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
            'Codigo'=>'required|unique:registro_lotes,Codigo',
            'NombreLote'=>'required',
            'hect'=>'required',
            'FechaRecoleccion'=>'required',
        ];
    }

    public function messages(): array
    {
        return [
            'hect.required' => 'Las Hectareas son requeridas' ,
            'Codigo.unique' => 'Este Codigo Ya fue Utilizado' ,
        ];
    }
}
