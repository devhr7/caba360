<?php

namespace App\Http\Requests\Config\Lote;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLoteRequest extends FormRequest
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
            'distrito' => 'required',
            'finca' => 'required',
            'lote' => 'required|max:50',
            'hect' => 'required|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'distrito.required' => 'El distrito es Requerido',
            'finca.required' => 'El finca es Requerido',
            'lote.required' => 'El Finca es Requerido',
            'lote.max' => 'No puede Registrar mas de :max caracteres',
            'hect.required' => 'Las Hectareas son requeridas',
            'hect.max' => 'No puede registrar mas de :max Hectareas',
            
        ];
    }
}
