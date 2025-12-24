<?php

namespace App\Http\Controllers\Modulos\Cumplidos\CumplidoAplicacion\Api;

use App\Http\Controllers\Controller;
use App\Models\CumplidoAplicacionProducto;
use App\Models\RecordVisita;
use Carbon\Carbon;


use function PHPUnit\Framework\isNull;

class CumplidoAplicacionController extends Controller
{

    /**
     * Esta función obtiene todos los cumplidos de aplicación.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllCumplidosAplicacion()
    {
        $cumplidoAplicacion = CumplidoAplicacionProducto::with(['labor', 'CumlidoAplicacion.finca.distrito', 'CumlidoAplicacion.finca.zona', 'CumlidoAplicacion.lote'])
            ->whereNotNull('labor_id');

        $cumplidos = $cumplidoAplicacion->get()->map(function ($item) {
            return [
                'codigo' => $item->CumlidoAplicacion->codigo,
                'fecha' => Carbon::parse($item->CumlidoAplicacion->fecha)->format('Y-m-d'),
                'fecha_cierre' => Carbon::parse($item->CumlidoAplicacion->fecha_cierre)->format('Y-m-d'),
                'HectLote' => $item->CumlidoAplicacion->HectLote,
                'finca' => $item->CumlidoAplicacion->finca->finca,
                'finca_id' => $item->CumlidoAplicacion->finca->id,
                'distrito' => $item->CumlidoAplicacion->finca->distrito->distrito,
                'distrito_id' => $item->CumlidoAplicacion->finca->distrito->id,
                'zona' => $item->CumlidoAplicacion->finca->zona->zona,
                'zona_id' => $item->CumlidoAplicacion->finca->zona->id,
                'codigo_lote' => $item->CumlidoAplicacion->reg_lote->Codigo,
                'codigo_lote_id' => $item->CumlidoAplicacion->reg_lote->id,

                'lote' => $item->CumlidoAplicacion->lote->lote,
                'lote_id' => $item->CumlidoAplicacion->lote->id,
                'contratista' => $item->CumlidoAplicacion->ResponsableAplicacion ? $item->CumlidoAplicacion->ResponsableAplicacion->nombre : null,
                'contratista_id' => $item->CumlidoAplicacion->ResponsableAplicacion ? $item->CumlidoAplicacion->ResponsableAplicacion->id : null,
                'labor' => $item->labor->labor,
                'labor_id' => $item->labor->id,
                'cantidad_total' => $item->cantidad_Total,
                'Cantidad_Hect' => in_array($item->labor->id, [330, 31, 32, 66]) ? $item->cantidad_Total : 0,
                'Cantidad_Bultos' => in_array($item->labor->id, [33, 34, 35, 36, 39, 49, 76]) ? $item->cantidad_Total : 0,
                'precio_total' => $item->PrecioTotal,
            ];
        });

        return response()->json($cumplidos);
    }

    public function getAllCumplidosAplicacionDetalle()
    {
        $cumplidoAplicacion = CumplidoAplicacionProducto::with(['labor', 'CumlidoAplicacion.finca.distrito', 'CumlidoAplicacion.finca.zona', 'CumlidoAplicacion.lote']);

        $cumplidos = $cumplidoAplicacion->get()->map(function ($item) {
            return [
                'codigo' => $item->CumlidoAplicacion->codigo,
                'fecha' => Carbon::parse($item->CumlidoAplicacion->fecha)->format('Y-m-d'),
                'finca' => $item->CumlidoAplicacion->finca->finca,
                'finca_id' => $item->CumlidoAplicacion->finca->id,
                'distrito' => $item->CumlidoAplicacion->finca->distrito->distrito,
                'distrito_id' => $item->CumlidoAplicacion->finca->distrito->id,
                'zona' => $item->CumlidoAplicacion->finca->zona->zona,
                'zona_id' => $item->CumlidoAplicacion->finca->zona->id,
                'codigo_lote' => $item->CumlidoAplicacion->reg_lote->Codigo,
                'hect_lote' => $item->CumlidoAplicacion->reg_lote->Hect,
                'codigo_lote_id' => $item->CumlidoAplicacion->reg_lote->id,
                'statusLote' => $item->CumlidoAplicacion->reg_lote->FechaRecoleccion ? 'Recolectado' : 'Activo',
                'FechaRecoleccion' => $item->CumlidoAplicacion->reg_lote->FechaRecoleccion,


                'lote' => $item->CumlidoAplicacion->lote->lote,
                'lote_id' => $item->CumlidoAplicacion->lote->id,
                'contratista' => $item->CumlidoAplicacion->ResponsableAplicacion ? $item->CumlidoAplicacion->ResponsableAplicacion->nombre : null,
                'contratista_id' => $item->CumlidoAplicacion->ResponsableAplicacion ? $item->CumlidoAplicacion->ResponsableAplicacion->id : null,
                'tipo' => $item->labor_id ? 'Labor' : 'Producto',

                'labor' => $item->labor_id ? $item->labor->labor : null,

                'record_visita' => $item->CumlidoAplicacion->RecordVisita_id ,
'fecha_record_visita' => (isset($item->CumlidoAplicacion) && !is_null($item->CumlidoAplicacion->RecordVisita_id)) ? optional(RecordVisita::find($item->CumlidoAplicacion->CodRecordVisita))->fecha : null,

              'GrupoMateriaPrima' => $item->GrupoMateriaPrima_id ? $item->grupoMPrima->GrupoMateriaPrima : null,
                'MateriaPrima' => $item->producto_id ? $item->Producto->MateriaPrima : null,
                'Dosis_por_Ha' => $item->Dosis_por_Ha,
                'cantidad_total' => $item->cantidad_Total,
                'precio_total' => $item->PrecioTotal,
                'Costo_Ha' => doubleval($item->PrecioTotal) / doubleval($item->CumlidoAplicacion->reg_lote->Hect),
                'fecha_cierre' => Carbon::parse($item->CumlidoAplicacion->fecha_cierre)->format('d/m/Y'),
            ];
        });

        return response()->json($cumplidos);
    }
}
