<?php

namespace App\Http\Requests\Modulos\Agronomo\RecordVisita;



use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;


class StoreProductoRecordVisitasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('mod.recordvisita.show');

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'TipoProducto' => ['required'],
            'Producto' => ['required'],
            'CantxHect' => ['required', 'numeric'],
            'Total' => ['required', 'numeric'],
            'FechaAplicacion' => ['nullable', 'date'],
        ];
    }
}
