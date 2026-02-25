<?php

namespace App\Http\Requests\Modulos\Agronomo\RecordVisita;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;


class UpdateRecordVisitaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('mod.recordvisita.edit');

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'CodigoRecord' => ['required', 'string', 'max:255'],
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
}
