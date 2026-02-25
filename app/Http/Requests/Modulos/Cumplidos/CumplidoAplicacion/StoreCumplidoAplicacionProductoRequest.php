<?php

namespace App\Http\Requests\Modulos\Cumplidos\CumplidoAplicacion;

use App\Models\CumplidoAplicacion;
use App\Models\CumplidoAplicacionProducto;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreCumplidoAplicacionProductoRequest extends FormRequest
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
            'CumplidoAplicacion' => [
                'required',
                'string',
                'exists:cumplido_aplicacions,slug',
            ],
            'TipoProducto' => ['required'],
            'Producto' => ['required', 'array', function ($attribute, $value, $fail) {
                $cumplidoAplicacionSlug = request('CumplidoAplicacion');
                $productoId = $value['id'];

                $cumplidoAplicacionId = CumplidoAplicacion::where('slug', $cumplidoAplicacionSlug)->first()->id;


                $count = CumplidoAplicacionProducto::where('CumplidoAplicacion_id', $cumplidoAplicacionId)
                    ->where('producto_id', $productoId)
                    ->count();

             
                if ($count >= 1 ) {
                    $fail('No se pueden agregar más de 2 productos iguales en un cumplido de aplicación.');
                }
            }],
            'CantxHect' => ['required', 'numeric'],
            'Total' => ['required', 'numeric'],
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
            'CumplidoAplicacion.required' => 'El cumplido de la aplicaci n es requerido',
            'CumplidoAplicacion.exists' => 'No se encontr  el cumplido de la aplicaci n',
            'TipoProducto.required' => 'El tipo de producto es requerido',
            'Producto.required' => 'El producto es requerido',
            'Producto.unique' => 'Ya se tiene agregado este producto',
            'CantxHect.required' => 'La cantidad por hect rea es requerida',
            'CantxHect.numeric' => 'La cantidad por hect rea debe ser un n mero',
            'Total.required' => 'El total es requerido',
            'Total.numeric' => 'El total debe ser un n mero',
        ];
    }
}
