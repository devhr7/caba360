<?php

namespace App\Http\Resources\Modulos\Record;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RecordCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($data) {
            return [
                'id' => $data->id,
                'slug' => $data->slug,
               
                'fecha' => \Carbon\Carbon::parse($data->recordVisita->fecha)->format('d/m/Y'),
                'RecordVisita' => $data->recordVisita->Codigo,
                'Finca' => $data->recordVisita->Finca->finca,
                'Lote' => $data->recordVisita->Lote->lote,
                'RegLote' => $data->recordVisita->RegistroLotes->Codigo,
                'Diagnostico' => $data->recordVisita->diagnostico,
                'hect_aplicacion' => $data->recordVisita->hect_aplicacion,
                'GrupoMateriaPrima' => $data->grupoMPrima->GrupoMateriaPrima,
                'Producto' => $data->Producto->MateriaPrima,
                'Dosis_por_Ha' => $data->Dosis_por_Ha,
                'cantidad_Total' => $data->cantidad_Total,
                'fecha_estimada_aplicacion' => $data->fecha_estimada_aplicacion,
                'Observaciones' => $data->recordVisita->observaciones,
            ];
        })->toArray();
    }
}
