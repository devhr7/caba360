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
        $cumplidoMaquinaria = CumplidoMaquinaria::with(['maquina', 'finca.distrito', 'finca.zona', 'registroLotes', 'lote', 'empleados', 'labor'])
            ->whereNotNull('RegLote_id');

        $cumplidos = $cumplidoMaquinaria->get()->map(function ($item) {
            return [

                'fecha' => Carbon::parse($item->fecha)->format('Y-m-d'),
                'codigo' => $item->Codigo,
                'zona' => $item->finca->zona->zona,
                'finca' => $item->finca->finca,
                'finca_id' => $item->finca->id,
                'lote' => $item->lote->lote,
                'lote_id' => $item->lote->id,
                'cod_lote' => $item->RegistroLotes->Codigo,
                'reg_lote_id' => $item->RegLote_id,
                'hect_lote' => $item->RegistroLotes->Hect,
                'identificacion_operario' => $item->empleados->identificacion,
                'operario' => $item->empleados->nombre1,
                'operario_id' => $item->empleados->id,
                'maquina' => $item->maquina->CodMaq,
                'maquina_id' => $item->maquina->id,
                'horometro_ini' => $item->HorometroInicial,
                'horometro_fin' => $item->HorometroFinal,
                'horometro_total' => ($item->HorometroInicial && $item->HorometroFinal && $item->HorometroInicial > 1 && $item->HorometroFinal > 1 && $item->HorometroFinal > $item->HorometroInicial) ? $item->HorometroFinal - $item->HorometroInicial : 0,
                'labor' => $item->labor->labor,
                'labor_id' => $item->labor->id,
                'distrito_id' => $item->finca->distrito->id,
                'total' => $item->valor_total,
                'cant_trab' => $item->cant,
                'bultos_trab' => in_array($item->labor_id, [28, 29]) ? $item->cant : 0,
                'hect_trab' => in_array($item->labor_id, [28, 29, 21]) ? 0 : $item->cant,
                'horas_trab' => in_array($item->labor_id, [21]) ? $item->cant : 0,
            ];
        });

        return response()->json($cumplidos);
    }
}
