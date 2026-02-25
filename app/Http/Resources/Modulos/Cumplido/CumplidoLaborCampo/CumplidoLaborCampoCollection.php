<?php

namespace App\Http\Resources\Modulos\Cumplido\CumplidoLaborCampo;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CumplidoLaborCampoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($reg) {
                return [
                    'cumplido' => $reg->CumpLaborcampo->codigo,
                    'fecha' => \Carbon\Carbon::parse($reg->CumpLaborcampo->fecha)->format('d/m/Y'),
                    'distrito' => is_null($reg->CumpLaborcampo->finca_id) ? null : $reg->CumpLaborcampo->Finca->Distrito->distrito,
                    'CentroCosto' => is_null($reg->CumpLaborcampo->finca_id) ? null : $reg->CumpLaborcampo->Finca->zona->CentroCosto,
                    'zona' => is_null($reg->CumpLaborcampo->finca_id) ? null : $reg->CumpLaborcampo->Finca->zona->zona,
                    'finca' => !is_null($reg->CumpLaborcampo->finca_id) ? $reg->CumpLaborcampo->Finca->finca : null,
                    'lote' => $reg->CumpLaborcampo->id ? $reg->CumpLaborcampo->cumplido_laborcampodetallelote->map(function ($lote) {
                        return $lote->RegLote->Lote->lote ?? null;
                    })->filter()->implode(', ') : null,
                    'identificacion' => $reg->personal_id ? $reg->Personal->identificacion : null,
                    'nombre' => $reg->personal_id ? $reg->Personal->nombre1 : null,
                    'labor' => $reg->CumpLaborcampo->labor_id ? $reg->CumpLaborcampo->Labor->labor : null,
                    'cantidad' => $reg->cantidad,
                    'costo_unit' => $reg->costo_unit,
                    'total_bonificacion' => $reg->total_bonificacion,
                    'CantidadTotal' => $reg->CumpLaborcampo->cantidadtotal,
                    'fecha_cierre' => $reg->CumpLaborcampo->fecha_cierre ? \Carbon\Carbon::parse($reg->CumpLaborcampo->fecha_cierre)->format('d/m/Y') : null,
                ];
            }),
        ];
    }
}
