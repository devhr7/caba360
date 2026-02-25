<?php

namespace App\Http\Requests\Modulos\Cumplidos\CumplidoOrdenServicio;


use Illuminate\Foundation\Http\FormRequest;

class StoreCumplidoOrdenServicioDetalleRequest extends FormRequest
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
            'claseCentroCosto' => 'required',
            'cantidad' => 'required|numeric',
            'unidadMedida' => 'required',
            'PrecioUnit' => 'required|numeric',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'claseCentroCosto.required' => 'El campo de clase de centro de costo es obligatorio.',
            'cantidad.required' => 'El campo de cantidad es obligatorio.',
            'cantidad.numeric' => 'El campo de cantidad debe ser numérico.',
            'unidadMedida.required' => 'El campo de unidad de medida es obligatorio.',
            'PrecioUnit.required' => 'El campo de precio unitario es obligatorio.',
            'PrecioUnit.numeric' => 'El campo de precio unitario debe ser numérico.',
        ];
    }
}
