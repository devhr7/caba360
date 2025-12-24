<?php

namespace App\Http\Requests\Config\Finca;


use Illuminate\Foundation\Http\FormRequest;

class UpdateFincaRequest extends FormRequest
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
            'finca' => 'required|max:50|unique:fincas',
            'distrito' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'distrito.required' => 'El distrito es Requerido',
            'finca.required' => 'El Finca es Requerido',
            'finca.max' => 'No puede Registrar mas de :max caracteres',
            'finca.unique'=> 'Este registro ya se encuentra en la base de datos',
        ];
    }
}
