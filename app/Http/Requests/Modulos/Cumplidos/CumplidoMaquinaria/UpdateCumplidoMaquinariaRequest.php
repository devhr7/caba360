<?php


namespace App\Http\Requests\Modulos\Cumplidos\CumplidoMaquinaria;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateCumplidoMaquinariaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('mod.cump_maq.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'CodigoCumplido' => [
                'required',
               
            ],
            'FechaCumplido' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (request()->FechaInicio && $value <= request()->FechaInicio) {
                        $fail('La fecha de cumplido debe ser mayor a la fecha de inicio.');
                    }
                },
            ],


            'empleado' => 'required',
            'Maquina_id' => 'required',
            'horometro_inicio' => 'nullable',
            'horometro_fin' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (request()->horometro_inicio && $value <= request()->horometro_inicio) {
                        $fail('El horometro fin tiene que ser superior al horometro de inicio del Lote.');
                    }
                },
            ],
            'GalACPM' => 'nullable',
            'labor' => 'required',
            'Cant' => [
                'required',
                'numeric',
            ],

        ];

        if ($this->Externo) {
            $rules['fincaExt'] = ['required', 'string', 'max:255'];
            $rules['loteExt'] = ['required', 'string', 'max:255'];
        } else {
            $rules['finca'] = ['required'];
            $rules['lote'] = ['required'];
            $rules['RegLote_id'] = ['required'];
            $rules['Hectareas'] = ['required'];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'CodigoCumplido.required' => 'Debe ingresar el codigo del cumplido.',
            'FechaCumplido.required' => 'Debe seleccionar la fecha del cumplido.',
            'finca.required' => 'Debe seleccionar una finca.',
            'lote.required' => 'Debe seleccionar un lote.',
            'RegLote_id.required' => 'Debe seleccionar un registro de lote.',
            'Hectareas.required' => 'Debe ingresar la cantidad de hectareas.',
            'empleado.required' => 'Debe seleccionar un empleado.',
            'Maquina_id.required' => 'Debe seleccionar una maquina.',
            'horometro_fin.after' => 'El horometro fin debe ser superior al horometro de inicio.',
            'GalACPM.nullable' => 'El campo galones de ACPM es opcional.',
            'labor.required' => 'Debe seleccionar una labor.',
            'Cant.required' => 'Debe ingresar la cantidad de la labor.',
            'Cant.numeric' => 'La cantidad debe ser un numero.',
            'Cant.max' => 'La cantidad no puede ser mayor que las hectareas.',
        ];
    }
}
