<?php

namespace App\Http\Controllers\Modulos\Costos\Report;

use App\Http\Controllers\Controller;
use App\Models\Finca;
use App\Models\Lote;
use App\Models\consolidadogasto;
use App\Models\consolidadoingreso;
use App\Models\CumplidoAplicacion;
use App\Models\CumplidoAplicacionProducto;
use App\Models\CumplidoMaquinaria;
use App\Models\CumplidoOrdenServicio;
use App\Models\CumplidoOrdenServicioDetalle;
use App\Models\gasto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RegistroLotes;

use App\Models\status;
use App\Models\subtipogasto;
use App\Models\TipoGasto;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Database\Seeders\ConsolidadoingresoSeeder;

class ReporteCostosController extends Controller
{
    //
    public function GastosPorLoteDetallado()
    {
        $data = ['options' => [
            'get_finca' => Finca::optionsFinca(),
            'get_lote' => Lote::optionsLote(),
            'get_reglote' => RegistroLotes::optionsRegLote([status::where("status", "Activo")->first()->id, status::where("status", "Bloqueado")->first()->id]),

        ]];

        return Inertia::render('Modulos/Costos/Reporte/ConsolidadoGastosLoteDetallado', $data);
    }

    public function GastosPorLoteDetalladoData(Request $request)
    {
        $id_regLote = $request->codigolote['id'];
        $regLote = RegistroLotes::find($id_regLote);
        $consolidadoGastos = consolidadogasto::getByRegLoteId($id_regLote);
        $data_consolidadogastos = consolidadogasto::select('gasto_id')->where('reglote_id', $id_regLote)->groupBy('gasto_id')->get();
        $totalGasto = $consolidadoGastos->sum('total');
        $hect = $regLote->Hect;
        $gastoxhect = $hect ? $totalGasto / $hect : 0;

        $data = [
            'finca' => $regLote->finca->finca,
            'lote' => $regLote->lote->lote,
            'codigo' => $regLote->Codigo,
            'hect' => $regLote->Hect,
            'gastoxhect' => number_format($gastoxhect, 2, ',', '.'),
            'gasto_total' => number_format($totalGasto, 2, ',', '.'),
            'data' => consolidadogasto::select('gasto_id')
                ->where('reglote_id', $id_regLote)
                ->groupBy('gasto_id')
                ->get()
                ->map(function ($item1) use ($id_regLote) {
                    $totalGasto1 = consolidadogasto::where('gasto_id', $item1->gasto_id)
                        ->where('reglote_id', $id_regLote)
                        ->sum('total');
                    $hect1 = $item1->first()->reglote->Hect;
                    return [
                        'nombre_gasto' => $item1->gasto->nombre,
                        'gastoxhect' => number_format($totalGasto1 / $hect1, 2, ',', '.'),
                        'gasto_total' => number_format($totalGasto1, 2, ',', '.'),
                        'data' => consolidadogasto::select('tipogasto_id')
                            ->where('gasto_id', $item1->gasto_id)
                            ->where('reglote_id', $id_regLote)
                            ->groupBy('tipogasto_id')
                            ->get()
                            ->map(function ($item2) use ($id_regLote) {
                                $totalGasto2 = consolidadogasto::where('tipogasto_id', $item2->tipogasto_id)
                                    ->where('reglote_id', $id_regLote)
                                    ->sum('total');
                                $hect2 = $item2->first()->reglote->Hect;
                                return [
                                    'nombre_tipogasto' => $item2->tipogasto->nombre,
                                    'gasto_total' => number_format($totalGasto2, 2, ',', '.'),
                                    'gastoxhect' => number_format($totalGasto2 / $hect2, 2, ',', '.'),
                                    'data' => consolidadogasto::select('subtipogasto_id')
                                        ->where('tipogasto_id', $item2->tipogasto_id)
                                        ->where('reglote_id', $id_regLote)
                                        ->groupBy('subtipogasto_id')
                                        ->get()
                                        ->map(function ($item3) use ($id_regLote) {
                                            $totalGasto3 = consolidadogasto::where('subtipogasto_id', $item3->subtipogasto_id)
                                                ->where('reglote_id', $id_regLote)
                                                ->sum('total');
                                            $hect3 = $item3->first()->reglote->Hect;
                                            return [
                                                'nombre_subtipogasto' => $item3->subtipogasto->nombre,
                                                'gasto_total' => number_format($totalGasto3, 2, ',', '.'),
                                                'gastoxhect' => number_format($totalGasto3 / $hect3, 2, ',', '.'),
                                            ];
                                        }),
                                ];
                            }),
                    ];
                }),
            'movcumplidos' => $this->getCumplidos($id_regLote),

        ];

        return json_encode($data);
    }

    public function GastosPorLote()
    {
        $data = [
            'options' => [
                'get_finca' => Finca::optionsFinca(),
                'get_lote' => Lote::optionsLote(),
                'get_reglote' => RegistroLotes::optionsRegLote([status::where("status", "Activo")->first()->id, status::where("status", "Bloqueado")->first()->id]),
            ]
        ];

        return Inertia::render('Modulos/Costos/Reporte/ConsolidadoGastosLote', $data);
    }

    public function GastosPorLoteData(Request $request)
    {
        $id_regLote = $request->codigolote['id'];
        $regLote = RegistroLotes::find($id_regLote);
        $consolidadoGastos = consolidadogasto::getByRegLoteId($id_regLote);
        $data_consolidadogastos = consolidadogasto::select('gasto_id')->where('reglote_id', $id_regLote)->groupBy('gasto_id')->get();
        $totalGasto = $consolidadoGastos->sum('total');
        $hect = $regLote->Hect;
        $gastoxhect = $hect ? $totalGasto / $hect : 0;

        $data = [
            'distrito' => $regLote->finca->distrito->distrito,
            'finca' => $regLote->finca->finca,
            'lote' => $regLote->lote->lote,
            'codigo' => $regLote->Codigo,
            'hect' => $regLote->Hect,
            'gastoxhect' => number_format($gastoxhect, 2, ',', '.'),
            'gasto_total' => number_format($totalGasto, 2, ',', '.'),
            'data' =>
            consolidadogasto::select('gasto_id')
                ->where('reglote_id', $id_regLote)
                ->groupBy('gasto_id')
                ->get()
                ->map(function ($item1) use ($id_regLote) {
                    $totalGasto1 = consolidadogasto::where('gasto_id', $item1->gasto_id)
                        ->where('reglote_id', $id_regLote)
                        ->sum('total');
                    $hect1 = $item1->first()->reglote->Hect;
                    return [
                        'nombre_gasto' => $item1->gasto->nombre,
                        'gastoxhect' => number_format($totalGasto1 / $hect1, 2, ',', '.'),
                        'gasto_total' => number_format($totalGasto1, 2, ',', '.'),
                        'data' => consolidadogasto::select('tipogasto_id')
                            ->where('gasto_id', $item1->gasto_id)
                            ->where('reglote_id', $id_regLote)
                            ->groupBy('tipogasto_id')
                            ->get()
                            ->map(function ($item2) use ($id_regLote) {
                                $totalGasto2 = consolidadogasto::where('tipogasto_id', $item2->tipogasto_id)
                                    ->where('reglote_id', $id_regLote)
                                    ->sum('total');
                                $hect2 = $item2->first()->reglote->Hect;
                                return [
                                    'nombre_tipogasto' => $item2->tipogasto->nombre,
                                    'gasto_total' => number_format($totalGasto2, 2, ',', '.'),
                                    'gastoxhect' => number_format($totalGasto2 / $hect2, 2, ',', '.'),
                                    'data' => consolidadogasto::select('subtipogasto_id')
                                        ->where('tipogasto_id', $item2->tipogasto_id)
                                        ->where('reglote_id', $id_regLote)
                                        ->groupBy('subtipogasto_id')
                                        ->get()
                                        ->map(function ($item3) use ($id_regLote) {
                                            $totalGasto3 = consolidadogasto::where('subtipogasto_id', $item3->subtipogasto_id)
                                                ->where('reglote_id', $id_regLote)
                                                ->sum('total');
                                            $hect3 = $item3->first()->reglote->Hect;
                                            return [
                                                'nombre_subtipogasto' => $item3->subtipogasto->nombre,
                                                'gasto_total' => number_format($totalGasto3, 2, ',', '.'),
                                                'gastoxhect' => number_format($totalGasto3 / $hect3, 2, ',', '.'),
                                            ];
                                        }),
                                ];
                            }),
                    ];
                }),
            'movcumplidos' => $this->getCumplidos($id_regLote),
            'consolidadoventas' => $this->getConsolidadoVentas($id_regLote),
            'mov_importados' => "",
        ];

        return json_encode($data);
    }

    public function getCumplidos($id_regLote)
    {
        // Consulta para Cumplidos de Maquinaria
        $cumplidosMaquinaria = CumplidoMaquinaria::select('fecha', 'Codigo', 'labor_id', 'cant', 'valor_total')
            ->where('reglote_id', $id_regLote)
            ->get()
            ->map(function ($item) {
                return [

                    'fecha' => \Carbon\Carbon::parse($item->fecha)->format('Y-m-d'),
                    'tipo' => "Labor Maquinaria",
                    'codigo' => $item->Codigo,
                    'labor' => $item->labor->labor,
                    'producto' => null,
                    'cantidad' => $item->cant,
                    'valor_unit' => number_format($item->valor_total / $item->cant, 2, ',', '.'),
                    'valor_total' => number_format($item->valor_total, 2, ',', '.'),
                ];
            });

        $cumplidosAplicacionProducto = CumplidoAplicacionProducto::select('CumplidoAplicacion_id', 'producto_id', 'labor_id', 'cantidad_Total', 'PrecioTotal', 'PrecioUnit')
            ->whereHas('CumlidoAplicacion', function ($query) use ($id_regLote) {
                $query->where('reg_lote_id', $id_regLote);
            })
            ->get()
            ->map(function ($item) {
                return [
                    //'fecha' => $item->CumlidoAplicacion->fecha,
                    'fecha' => \Carbon\Carbon::parse($item->CumlidoAplicacion->fecha)->format('Y-m-d'),
                    'tipo' => "Cumplido de Aplicacion",
                    'codigo' => $item->CumlidoAplicacion->codigo,
                    'labor' => $item->labor ? $item->labor->labor : null,
                    'GrupoProducto' => $item->Producto ? $item->Producto->grupoMPrima->GrupoMateriaPrima : null,
                    'producto' => $item->Producto ? $item->Producto->MateriaPrima : null,
                    'cantidad' => $item->cantidad_Total,
                    'valor_unit' => number_format($item->PrecioUnit, 2, ',', '.'),
                    'valor_total' => number_format($item->PrecioTotal, 2, ',', '.'),
                ];
            });


        // Consulta para Cumplidos de Orden de Servicio
        $cumplidosOrdenServicio = CumplidoOrdenServicioDetalle::with('CumplidoOrdenServicio')->select('CumplidoOrdenServicio_id', 'Labor_id', 'Cantidad', 'DetalleLabor', 'ValorUnit', 'Total')
            ->whereHas('CumplidoOrdenServicio', function ($query) use ($id_regLote) {
                $query->where('RegLote_id', $id_regLote);
            })
            ->get()
            ->map(function ($item) {
                return [
                    'fecha' => \Carbon\Carbon::parse($item->cumplidoordenservicio->fecha)->format('Y-m-d'),

                    'tipo' => "Orden de Servicio",
                    'codigo' => $item->cumplidoordenservicio->codigo,
                    'labor' => $item->Labor_id ? $item->Labor->labor . ". " . $item->DetalleLabor : $item->DetalleLabor,
                    'producto' => null,
                    'cantidad' => $item->Cantidad,
                    'valor_unit' => number_format($item->ValorUnit, 2, ',', '.'),
                    'valor_total' => number_format($item->Total, 2, ',', '.'),
                ];
            });


        // Combinar y ordenar resultados
        $cumplidos = $cumplidosAplicacionProducto->merge($cumplidosMaquinaria)
            ->merge($cumplidosOrdenServicio)
            ->sortByDesc('fecha')
            ->values(); // Resetea los índices de la colección

        // Formatear los datos en un solo JSON


        return $cumplidos->sortByDesc('fecha');
    }

    public function getCumplidosapi()
    {

        $cumplidosAplicacionProducto = CumplidoAplicacionProducto::with('CumlidoAplicacion')->select('CumplidoAplicacion_id', 'producto_id', 'labor_id', 'cantidad_Total', 'PrecioTotal', 'PrecioUnit')
            ->whereHas('CumlidoAplicacion', function ($query) {
                $query->whereNotNull('labor_id');
            })
            ->get()
            ->map(function ($item) {
                return [
                    //'fecha' => $item->CumlidoAplicacion->fecha,
                    'fecha' => \Carbon\Carbon::parse($item->CumlidoAplicacion->fecha)->format('d/m/Y'),
                    'tipo' => "Cumplido de Aplicacion",
                    'codigo' => $item->CumlidoAplicacion->codigo,
                    'Distrito' => $item->CumlidoAplicacion->reg_lote->finca->distrito->distrito,
                    'Zona' => $item->CumlidoAplicacion->reg_lote->finca->zona->zona,
                    'Finca' => $item->CumlidoAplicacion->reg_lote->Finca->finca,
                    'Lote' => $item->CumlidoAplicacion->reg_lote->Lote->lote,
                    'CodigoLote' => $item->CumlidoAplicacion->reg_lote->Codigo,
                    'HectLote' => $item->CumlidoAplicacion->reg_lote->Hect,
                    'contratista_nombre' => $item->CumlidoAplicacion->ResponsableAplicacion->nombre,
                    'contratista_identificacion' => $item->CumlidoAplicacion->ResponsableAplicacion->identificacion,
                    'salida_almacen' => $item->CumlidoAplicacion->CodSalidaAlmacen,
                    'labor' => $item->labor ? $item->labor->labor : null,
                    'cantidad' => $item->cantidad_Total,
                    'valor_unit' => number_format($item->PrecioUnit, 2, ',', '.'),
                    'valor_total' => number_format($item->PrecioTotal, 2, ',', '.'),
                    'observaciones' => $item->CumlidoAplicacion->Observaciones,
                    'factura' => $item->CumlidoAplicacion->factura,
                    'fecha_cierre' => $item->CumlidoAplicacion->fecha_cierre,
                ];
            });


        // Consulta para Cumplidos de Orden de Servicio
        $cumplidosOrdenServicio = CumplidoOrdenServicioDetalle::with('CumplidoOrdenServicio', 'ClasificacionCentroCosto', 'UnidadMedida')->select('CumplidoOrdenServicio_id', 'TipoCentroCosto_id', 'UnidadMedida_id', 'Interno', 'DestinoServicio', 'finca_id', 'RegLote_id', 'Lote_id', 'Labor_id', 'Cantidad', 'DetalleLabor', 'ValorUnit', 'Total')
            ->get()
            ->map(function ($item) {
                return [

                    'fecha' => Carbon::parse($item->cumplidoordenservicio->fecha)->format('d/m/Y'),

                    'tipo' => "Orden de Servicio",
                    'codigo' => $item->cumplidoordenservicio->codigo,

                    'CentroCosto' => $item->TipoCentroCosto_id ? $item->ClasificacionCentroCosto->ClaseCentroCosto : null,
                    'destino' => $item->DestinoServicio,
                    'Distrito' => $item->finca_id ?  $item->Finca->Distrito->distrito : null,
                    'Zona' => $item->finca_id ? ($item->Finca->zona_id ? $item->Finca->zona->zona : null) : null,
                    'Finca' => $item->finca_id ?  $item->Finca->finca : null,
                    'Lote' => $item->Lote_id ?  $item->Lote->lote : null,
                    'CodigoLote' => $item->RegLote_id ?  $item->RegLote->Codigo : null,
                    'HectLote' => $item->RegLote_id ?  $item->RegLote->Hect : null,

                    'contratista_nombre' => $item->CumplidoOrdenServicio->contratista->nombre,
                    'contratista_identificacion' => $item->CumplidoOrdenServicio->contratista->identificacion,
                    'salida_almacen' => $item->CumplidoOrdenServicio->CodSalidaAlmacen,

                    'labor' => $item->Labor_id ? $item->Labor->labor . ". " . $item->DetalleLabor : $item->DetalleLabor,

                    'cantidad' => $item->Cantidad,
                    'unidad_medida' => $item->UnidadMedida->UnidadMedida,
                    'valor_unit' => $item->ValorUnit,
                    'valor_total' => $item->Total,
                    'observaciones' => $item->cumplidoordenservicio->Observaciones,
                    'factura' => $item->cumplidoordenservicio->factura,
                    'fecha_cierre' => \Carbon\Carbon::parse($item->cumplidoordenservicio->fecha_cierre)->format('d-m-Y'),





                ];
            });


        // Combinar y ordenar resultados
        $cumplidos = $cumplidosAplicacionProducto->merge($cumplidosOrdenServicio)
            ->sortByDesc('fecha')
            ->values(); // Resetea los índices de la colección

        // Formatear los datos en un solo JSON


        return $cumplidos->sortByDesc('fecha');
    }

    public function getConsolidadoVentas($id_regLote)
    {


        // Consulta para consolidado de gastos
        $consolidadoGastos = consolidadogasto::where('reglote_id', $id_regLote)->get();
        $totalGasto = $consolidadoGastos->sum('total');

        // Consulta para consolidado de ingresos
        $consolidadoIngresos = consolidadoingreso::where('reglote_id', $id_regLote)->get();
        $totalIngresos = $consolidadoIngresos->sum('totalventa');
        $totalKilos = $consolidadoIngresos->sum('kilogramos');

        // Obtener datos del lote
        $regLote = RegistroLotes::find($id_regLote);
        $hect = $regLote->Hect;

        // Calcular Gasto por Hectárea e Ingresos por Hectárea
        $gastoXHect = $hect ? $totalGasto / $hect : 0;
        $ingresosXHect = $hect ? $totalIngresos / $hect : 0;

        // Formatear los datos en un solo array
        $data = [
            'distrito' => $regLote->distrito->distrito,
            'finca' => $regLote->finca->finca,
            'lote' => $regLote->lote->lote,
            'codigo' => $regLote->Codigo,
            'fecha_inicio' => $regLote->FechaInicio,
            'fecha_siembre' => $regLote->FechaSiembra,
            'fecha_germinacion' => $regLote->FechaGerminacion,
            'fecha_recoleccion' => $regLote->FechaRecoleccion,
            'hect' => $regLote->Hect,
            'total_gasto' => number_format($totalGasto, 2, ',', '.'),
            'gasto_x_hect' => number_format($gastoXHect, 2, ',', '.'),
            'total_ingresos' => number_format($totalIngresos, 2, ',', '.'),
            'ingresos_x_hect' => number_format($ingresosXHect, 2, ',', '.'),
            'utilidad' => number_format($totalIngresos - $totalGasto, 2, ',', '.'),
            'utilidad_x_hect' => $hect ? number_format(($totalIngresos - $totalGasto) / $hect, 2, ',', '.') : 0,
            'total_kilos' => number_format($totalKilos, 2, ',', '.'),
            'costo_x_kilo' => $totalKilos ? number_format($totalGasto / $totalKilos, 2, ',', '.') : 0,
            'Bultos' => $regLote->Hect ? number_format($totalKilos / 62.5 / $regLote->Hect, 2, ',', '.') : 0,
            'Bultos' => $regLote->Hect ? number_format($totalKilos / 62.5 / $regLote->Hect, 2, ',', '.') : 0,
        ];

        return $data;
    }

    public function exportGastosPorLoteDetalladoPDF(Request $request)
    {
        $data = $this->GastosPorLoteDetalladoData($request);
        $pdf = Pdf::loadView('pdf.reporte_gastos_detallado', ['data' => json_decode($data, true)]);
        return $pdf->download('ReporteGastosDetallado.pdf');
    }
}
