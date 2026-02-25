<?php

namespace App\Http\Requests\Modulos\RegistroLote;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;


class UpdateRegistroLotesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('mod.reglote.edit');
        
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'FechaInicio' => 'required|date',
            'FechaSiembra' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && is_null($this->FechaInicio)) {
                        $fail('La fecha de siembra no puede ser mayor a la fecha de inicio si esta no existe.');
                    }
                    if (!is_null($value) && !is_null($this->FechaInicio) && $value <= $this->FechaInicio) {
                        $fail('La fecha de siembra debe ser mayor a la fecha de inicio.');
                    }
                },
            ],
            'FechaGerminacion' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && is_null($this->FechaSiembra)) {
                        $fail('La fecha de germinacion no puede ser mayor a la fecha de siembra si esta no existe.');
                    }
                    if (!is_null($value) && !is_null($this->FechaSiembra) && $value <= $this->FechaSiembra) {
                        $fail('La fecha de germinacion debe ser mayor a la fecha de siembra.');
                    }
                    if (!is_null($this->FechaRecoleccion) && is_null($value)) {
                        $fail('La fecha de germinacion no puede ser mayor a la fecha de recoleccion si esta no existe.');
                    }
                },
            ],
            'FechaRecoleccion' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && is_null($this->FechaGerminacion)) {
                        $fail('La fecha de recoleccion no puede ser mayor a la fecha de germinacion si esta no existe.');
                    }
                    if (!is_null($value) && !is_null($this->FechaGerminacion) && $value <= $this->FechaGerminacion) {
                        $fail('La fecha de recoleccion debe ser mayor a la fecha de germinacion.');
                    }
                    if (!is_null($this->FechaVenta) && is_null($value)) {
                        $fail('La fecha de recoleccion no puede ser mayor a la fecha de venta si esta no existe.');
                    }
                },
            ],
            'FechaVenta' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && is_null($this->FechaRecoleccion)) {
                        $fail('La fecha de venta no puede ser mayor a la fecha de recoleccion si esta no existe.');
                    }
                    if (!is_null($value) && !is_null($this->FechaRecoleccion) && $value <= $this->FechaRecoleccion) {
                        $fail('La fecha de venta debe ser mayor a la fecha de recoleccion.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'FechaInicio.required' => 'La fecha de inicio es requerida.',
            'FechaInicio.date' => 'La fecha de inicio debe tener un formato de fecha (dd/mm/yyyy).',
            'FechaSiembra.date' => 'La fecha de siembra debe tener un formato de fecha (dd/mm/yyyy).',
            'FechaRecoleccion.date' => 'La fecha de recoleccion debe tener un formato de fecha (dd/mm/yyyy).',
        ];
    }
}
