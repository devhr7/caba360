<?php

namespace App\Http\Controllers\Modulos\RegistroLote\Api;

use App\Http\Controllers\Controller;
use App\Models\RegistroLotes;


class RegistroLotesController extends Controller
{
 
    public function LotesActivos()
    {
        $lotes = RegistroLotes::where('status_id', 1)
                              ->whereNull('FechaRecoleccion')
                              ->get();
        $lotes = $lotes->map(function ($lote) {
            return [
            'Codigo' => $lote->Codigo,
            'Distrito' => $lote->distrito_id ? $lote->distrito->distrito : '',
            'Finca' => $lote->finca_id ? $lote->finca->finca : '',
            'Lote Teorico' => $lote->NombreLote,
            'Lote' => $lote->lote_id ? $lote->lote->lote : '',
            'NombreLote' => $lote->NombreLote,
            'HectTeorico' => $lote->lote_id ? $lote->lote->hect : '',
            'Hectareas' => $lote->Hect,
            'FechaInicio' => $lote->FechaInicio ? \Carbon\Carbon::parse($lote->FechaInicio)->format('d/m/Y') : null,
            'FechaSiembra' => $lote->FechaSiembra ? \Carbon\Carbon::parse($lote->FechaSiembra)->format('d/m/Y') : null,
            'FechaGerminacion' => $lote->FechaGerminacion ? \Carbon\Carbon::parse($lote->FechaGerminacion)->format('d/m/Y') : null,
            'FechaRecoleccion' => $lote->FechaRecoleccion ? \Carbon\Carbon::parse($lote->FechaRecoleccion)->format('d/m/Y') : null,
            ];
        });

        return response()->json($lotes);
    }

}