<?php

namespace App\Http\Controllers\Modulos\Cumplidos\CumplidoOrdenServicio\Api;

use App\Http\Controllers\Controller;
use App\Models\CumplidoAplicacion;
use App\Models\CumplidoOrdenServicioDetalle;
use Carbon\Carbon;


use function PHPUnit\Framework\isNull;

class ApiCumplidoOrdenServicioController extends Controller
{

    /**
     * Esta función obtiene todos los cumplidos de aplicación.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllOrdenesServicio()
    {

        $cumplidoOrdenServicio = CumplidoOrdenServicioDetalle::with(['CumplidoOrdenServicio'])
            ->get()->map(function ($data) {
                return [
                    'cumplido' => $data->CumplidoOrdenServicio->codigo,
                    'fecha' => Carbon::parse($data->CumplidoOrdenServicio->fecha)->format('d/m/Y'),
                    'fecha_cierre' => Carbon::parse($data->CumplidoOrdenServicio->fecha_cierre)->format('Y-m-d'),

                    'contratista_identificacion' => $data->CumplidoOrdenServicio->contratista->identificacion,
                    'contratista_nombre' => $data->CumplidoOrdenServicio->contratista->nombre,
                    'TipoCentroCosto' => $data->ClasificacionCentroCosto->ClaseCentroCosto,
                    'Distrito' => is_null($data->finca_id) ? "" :  $data->Finca->Distrito->distrito,
                    'Zona' => is_null($data->finca_id) ? "" : (is_null($data->Finca->zona_id) ? "" : $data->Finca->zona->zona),
                    'Finca' => is_null($data->finca_id) ? "" :  $data->Finca->finca,
                    'Lote' => is_null($data->Lote_id) ? "" : $data->Lote->lote,
                    'CodigoLote' => is_null($data->RegLote_id) ? "" :  $data->RegLote->Codigo,
                    'Destino' => $data->DestinoServicio,
                    'Labor' =>  is_null($data->Labor_id) ? "" :  $data->Labor->labor,
                    'Potrero' => is_null($data->potreto_id) ? "": $data->potrero->nombre,
                    'DetalleLabor' => $data->DetalleLabor,
                    'Cantidad' => $data->Cantidad,
                    'UnidadMedida' => is_null($data->UnidadMedida_id) ? "" :  $data->UnidadMedida->UnidadMedida,
                    'PrecioUnitario' => $data->ValorUnit,
                    'Total' => $data->Total,
                    'Autorizado_identificacion' => is_null($data->CumplidoOrdenServicio->Autorizacion_id) ? "" : $data->CumplidoOrdenServicio->Empleados->identificacion,
                    'Autorizado_nombre' => is_null($data->CumplidoOrdenServicio->Autorizacion_id) ? "" :  $data->CumplidoOrdenServicio->Empleados->nombre1,
                    'factura' => $data->CumplidoOrdenServicio->factura,
                    'fecha_verificado' => Carbon::parse($data->CumplidoOrdenServicio->fecha_cierre)->format('d/m/Y'),

                ];
            });

        return response()->json($cumplidoOrdenServicio);
    }

    public function getAllOrdServYCumpApli()
    {
        // Obtener cumplidos de orden de servicio
        $cumplidosOrdenServicio = CumplidoOrdenServicioDetalle::with(['CumplidoOrdenServicio','potrero', 'ClasificacionCentroCosto', 'Finca.Distrito', 'Finca.Zona', 'Lote', 'RegLote'])
            ->get()
            ->map(function ($data) {
                return [
                    'tipo' => 'OrdenServicio',
                    'cumplido' => $data->CumplidoOrdenServicio->codigo,
                    'fecha' => Carbon::parse($data->CumplidoOrdenServicio->fecha)->format('d/m/Y'),
                    'fecha_cierre' => Carbon::parse($data->CumplidoOrdenServicio->fecha_cierre)->format('Y-m-d'),
                    'contratista_identificacion' => $data->CumplidoOrdenServicio->contratista->identificacion,
                    'contratista_nombre' => $data->CumplidoOrdenServicio->contratista->nombre,
                    'TipoCentroCosto' => $data->ClasificacionCentroCosto->ClaseCentroCosto,
                    'Distrito' => is_null($data->finca_id) ? "" : $data->Finca->Distrito->distrito,
                    'Zona' => optional(optional($data->Finca)->zona)->zona ?? "",
                    'Finca' => is_null($data->finca_id) ? "" : $data->Finca->finca,
                    'Lote' => is_null($data->Lote_id) ? "" : $data->Lote->lote,
                    'Potrero' => is_null($data->potrero_id) ? "" : $data->potrero->nombre,
                    'CodigoLote' => is_null($data->RegLote_id) ? "" : $data->RegLote->Codigo,
                    'Destino' => $data->DestinoServicio,
                ];
            });

        // Obtener cumplidos de aplicación
        $cumplidosAplicacion = CumplidoAplicacion::with(['Finca.Distrito', 'Finca.Zona', 'Lote', 'RegLote'])
            ->get()
            ->map(function ($data) {
                $finca = $data->Finca;
                return [
                    'tipo' => 'Aplicacion',
                    'cumplido' => $data->codigo,
                    'fecha' => Carbon::parse($data->fecha)->format('d/m/Y'),
                    'fecha_cierre' => Carbon::parse($data->fecha_cierre)->format('Y-m-d'),
                    'Distrito' => is_null($data->finca_id) ? "" : $data->Finca->Distrito->distrito,
                    'Zona' =>  ($finca?->Zona?->zona ?? $finca?->zona?->zona) ?? "",
                    'Finca' => is_null($data->finca_id) ? "" : $data->Finca->finca,
                    'Lote' => is_null($data->Lote_id) ? "" : $data->Lote->lote,
                    'CodigoLote' => is_null($data->RegLote_id) ? "" : $data->RegLote->Codigo,
                ];
            });

        // Combinar ambos resultados
        $cumplidos = $cumplidosOrdenServicio->merge($cumplidosAplicacion);

        return response()->json($cumplidos);
    }
}
