<?php

namespace App\Http\Requests\Config\Distrito;

use Illuminate\Foundation\Http\FormRequest;

class StoreDistritoRequest extends FormRequest
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
            'distrito' => 'required|max:50|unique:distritos',
            
        ];
    }

    public function messages(): array
    {
        return [
            'distrito.required' => 'El distrito es Requerido',
            'distrito.max' => 'No puede Registrar mas de :max caracteres',
            'distrito.unique'=> 'Este registro ya se encuentra en la base de datos',
        ];
    }
}
