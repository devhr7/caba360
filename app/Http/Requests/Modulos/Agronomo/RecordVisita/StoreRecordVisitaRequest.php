<?php

namespace App\Http\Requests\Modulos\Agronomo\RecordVisita;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreRecordVisitaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('mod.recordvisita.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'CodigoRecord' => ['required', 'string', 'max:255', 'unique:App\Models\RecordVisita,Codigo'],
            'FechaRecord' => ['required', 'date'],
            'finca' => ['required', 'array'],
            'finca.id' => ['required', 'exists:App\Models\Finca,id'],
            'lote' => ['required', 'array'],
            'lote.id' => ['required', 'exists:App\Models\Lote,id'],
            'RegLote_id'=>['required'],
            'Hectareas' => ['required', 'numeric'],
            'Diagnostico' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'CodigoRecord.required' => 'El :attribute es requerido',
            'FechaRecord.required' => 'La :attribute es requerida',
            'finca.required' => 'La :attribute es requerida',
            'finca.id.required' => 'La :attribute es requerida',
            'finca.id.exists' => 'La :attribute no existe',
            'lote.required' => 'El :attribute es requerido',
            'lote.id.required' => 'La :attribute es requerida',
            'lote.id.exists' => 'La :attribute no existe',
            'RegLote_id.required' => 'El :attribute es requerido',
            'Hectareas.required' => 'La :attribute es requerida',
            'Hectareas.numeric' => 'La :attribute debe ser un numero',
            'Diagnostico.required' => 'El :attribute es requerido',
            'Observaciones.required' => 'El :attribute es requerido',
        ];
    }
}
