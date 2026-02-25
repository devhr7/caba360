<?php

namespace App\Http\Controllers\Modulos\Cumplidos\CumplidoMaquinaria\Api;

use App\Http\Controllers\Controller;
use App\Models\CumplidoMaquinaria;
use Carbon\Carbon;


use function PHPUnit\Framework\isNull;

class CumplidoMaquinariaController extends Controller
{

    /**
     * Esta función obtiene todos los cumplidos de aplicación.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllCumplidosMaquinaria()
    {
        $cumplidoMaquinaria = CumplidoMaquinaria::with(['maquina', 'finca.distrito', 'finca.zona', 'registroLotes', 'lote', 'empleados', 'labor']);

        $cumplidos = $cumplidoMaquinaria->get()->map(function ($item) {
            return [

                'fecha' => $item->fecha,
                'fecha_cierre' => $item->fecha_cierre,
                'codigo' => $item->Codigo,
                'Externo_Interno' => boolval($item->Externo) ? 'Externo' : 'Interno',
                'FincaExt' => $item->Finca,
                'LoteExt' => $item->Lote,
                'zona' => $item->finca ? $item->finca->zona->zona : '',
                'distrito' => $item->finca ? $item->finca->distrito->distrito : '',
                'finca' => $item->finca ? $item->finca->finca : '',
                'finca_id' => $item->finca_id ? $item->finca_id : '',
                'lote' => $item->lote ? $item->lote->lote : '',
                'lote_id' => $item->lote_id ? $item->lote_id : '',
                'cod_lote' => $item->RegistroLotes ? $item->RegistroLotes->Codigo : '',
                'reg_lote_id' => $item->RegLote_id ? $item->RegLote_id : '',
                'hect_lote' => $item->RegistroLotes ? $item->RegistroLotes->Hect : '',
                'identificacion_operario' => $item->empleados->identificacion,
                'operario' => $item->empleados->nombre1,
                'operario_id' => $item->empleados->id,
                'maquina' => $item->maquina->CodMaq,
                'maquina_id' => $item->maquina->id,
                'horometro_ini' => $item->HorometroInicial,
                'horometro_fin' => $item->HorometroFinal,
                'horometro_total' => ($item->HorometroInicial && $item->HorometroFinal && $item->HorometroInicial > 1 && $item->HorometroFinal > 1 && $item->HorometroFinal > $item->HorometroInicial) ? $item->HorometroFinal - $item->HorometroInicial : 0,
                'GalCombustible' => $item->GalCombustible,
                'labor' => $item->labor->labor,
                'labor_id' => $item->labor_id,
                'cant_trab' => $item->cant,
                'valor_unit' => $item->valor_unit,
                'valor_total' => $item->valor_total,
                'bultos_trab' => in_array($item->labor_id, [28, 29]) ? $item->cant : 0,
                'hect_trab' => in_array($item->labor_id, [28, 29, 21]) ? 0 : $item->cant,
                'horas_trab' => in_array($item->labor_id, [21]) ? $item->cant : 0,
                
            ];
        });

        return response()->json($cumplidos);
    }
}
