<?php

namespace App\Http\Requests\Modulos\Cumplidos\CumplidoAplicacion;

use App\Models\RegistroLotes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreCumplidoAplicacionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('mod.cump_aplicacion.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'CodigoCumplido' => ['required', 'string', 'max:100', 'unique:cumplido_aplicacions,codigo'],
            'FechaCumplido' => ['required', 'date'],
            'finca' => ['required', 'array'],
            'finca.id' => ['required', 'integer'],
            'lote' => ['required', 'array'],
            'lote.id' => ['required', 'integer'],
            'RegLote_id' => ['required', 'integer'],
            'Hectareas' => ['required', 'numeric',],
            /**
            'Hectareas' => ['required', 'numeric', function ($attribute, $value, $fail) {
                $registroLote = RegistroLotes::findOrFail($this->request->get('RegLote_id'));
                if ($registroLote->Hect < $value) {
                    $fail('La cantidad de hectareas aplicadas no puede ser mayor que las hectareas del registro de lote.');
                }
            }],
             */
            'labor' => ['required'],
            'RecordVisita' => ['nullable', 'integer'],
            'SalidaAlmacen' => ['nullable', 'integer'],
            'RiesgoLluvia' => ['boolean'],
            'Brisa' => ['boolean'],
            'Humedad' => ['boolean'],
            'Seguridad' => ['boolean'],
            'Empaques' => ['boolean'],
            'Velocidad' => ['nullable', 'numeric'],
            'ResponsableAplicacion' => ['required', 'array'],
            'ResponsableAplicacion.id' => ['required', 'integer'],
            'Observaciones' => ['nullable', 'string', 'max:1000'],

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
            'CodigoCumplido.required' => 'El campo Codigo del cumplido es obligatorio.',
            'CodigoCumplido.string' => 'El campo Codigo del cumplido debe ser un string.',
            'CodigoCumplido.max' => 'El campo Codigo del cumplido debe ser menor o igual a 100 caracteres.',
            'CodigoCumplido.unique' => 'Ya existe un cumplido con este Codigo.',
            'FechaCumplido.required' => 'El campo Fecha del cumplido es obligatorio.',
            'FechaCumplido.date' => 'El campo Fecha del cumplido debe ser una fecha valida.',
            'finca.required' => 'El campo finca es obligatorio.',
            'finca.id.required' => 'El campo finca.id es obligatorio.',
            'finca.id.integer' => 'El campo finca.id debe ser un entero.',
            'lote.required' => 'El campo lote es obligatorio.',
            'lote.id.required' => 'El campo lote.id es obligatorio.',
            'lote.id.integer' => 'El campo lote.id debe ser un entero.',
            'RegLote_id.required' => 'El campo RegLote_id es obligatorio.',
            'RegLote_id.integer' => 'El campo RegLote_id debe ser un entero.',
            'Hectareas.required' => 'El campo Hectareas es obligatorio.',
            'Hectareas.numeric' => 'El campo Hectareas debe ser un numero.',
            'Hectareas.max' => 'La cantidad de hectareas no puede ser mayor que las hectareas del registro de lote.',
            'RecordVisita.integer' => 'El campo RecordVisita debe ser un entero.',
            'SalidaAlmacen.integer' => 'El campo SalidaAlmacen debe ser un entero.',
            'RiesgoLluvia.boolean' => 'El campo RiesgoLluvia debe ser un booleano.',
            'Brisa.boolean' => 'El campo Brisa debe ser un booleano.',
            'Humedad.boolean' => 'El campo Humedad debe ser un booleano.',
            'Seguridad.boolean' => 'El campo Seguridad debe ser un booleano.',
            'Empaques.boolean' => 'El campo Empaques debe ser un booleano.',
            'Velocidad.numeric' => 'El campo Velocidad debe ser un numero.',
            'ResponsableAplicacion.id.integer' => 'El campo ResponsableAplicacion.id debe ser un entero.',
            'Observaciones.string' => 'El campo Observaciones debe ser un string.',
            'Observaciones.max' => 'El campo Observaciones debe ser menor o igual a 1000 caracteres.',
        ];
    }
}
