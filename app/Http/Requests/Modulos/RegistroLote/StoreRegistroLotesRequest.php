<?php

namespace App\Http\Requests\Modulos\RegistroLote;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;


class StoreRegistroLotesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Validacion de Permisos
        return Gate::allows('mod.reglote.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'NombreLote' => 'required',
            'hect' => 'required',
            'FechaInicio' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'hect.required' => 'Las Hectareas son requeridas',
            'Codigo.unique' => 'Este Codigo Ya fue Utilizado',
        ];
    }
}
