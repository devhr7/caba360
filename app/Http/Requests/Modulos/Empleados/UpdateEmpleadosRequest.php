<?php

namespace App\Http\Requests\Modulos\Empleados;


use Illuminate\Foundation\Http\FormRequest;

class UpdateEmpleadosRequest extends FormRequest
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
            'identificacion' => 'required|unique:empleados,identificacion,' . $this->route('id'),
            'nombre' => 'required|string|max:255',
            'cargo' => 'required|array',


        ];
    }
}
