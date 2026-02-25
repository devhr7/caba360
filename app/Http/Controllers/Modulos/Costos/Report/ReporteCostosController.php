<?php

namespace App\Http\Controllers\Modulos\Costos\Report;

use App\Http\Controllers\Controller;
use App\Models\Finca;
use App\Models\Lote;
use App\Models\consolidadogasto;
use App\Models\consolidadoingreso;
use App\Models\cumplido_laborcampodetallecuadrilla;
use App\Models\cumplido_laborcampodetallelote;
use App\Models\CumplidoAplicacion;
use App\Models\CumplidoAplicacionProducto;
use App\Models\CumplidoLaboresCampo;
use App\Models\CumplidoMaquinaria;
use App\Models\CumplidoOrdenServicio;
use App\Models\CumplidoOrdenServicioDetalle;
use App\Models\gasto;
use App\Models\ppt_detalle_costo;
use App\Models\ProductoRecordVisitas;
use App\Models\RecordVisita;
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
            'gastoxhect' => number_format($gastoxhect, 0, ',', '.'),
            'gasto_total' => number_format($totalGasto, 0, ',', '.'),
            'data' =>
            consolidadogasto::select('gasto_id')
                ->where('reglote_id', $id_regLote)
                ->groupBy('gasto_id')
                ->get()
                ->map(function ($item1) use ($id_regLote, $hect) {
                    $totalGasto1 = consolidadogasto::where('gasto_id', $item1->gasto_id)
                        ->where('reglote_id', $id_regLote)
                        ->sum('total');
                    $hect1 = $item1->first()->reglote->Hect;
                    return [
                        'nombre_gasto' => $item1->gasto->nombre,
                        'hect' => $hect,
                        'gastoxhect' => number_format($totalGasto1 / $hect, 0, ',', '.'),
                        'gasto_total' => number_format($totalGasto1, 0, ',', '.'),
                        'data' => consolidadogasto::select('tipogasto_id')
                            ->where('gasto_id', $item1->gasto_id)
                            ->where('reglote_id', $id_regLote)
                            ->groupBy('tipogasto_id')
                            ->get()
                            ->map(function ($item2) use ($id_regLote, $hect) {
                                $totalGasto2 = consolidadogasto::where('tipogasto_id', $item2->tipogasto_id)
                                    ->where('reglote_id', $id_regLote)
                                    ->sum('total');
                                $hect2 = $item2->first()->reglote->Hect;
                                return [
                                    'nombre_tipogasto' => $item2->tipogasto->nombre,
                                    'gasto_total' => number_format($totalGasto2, 0, ',', '.'),
                                    'gastoxhect' => number_format($totalGasto2 / $hect, 0, ',', '.'),
                                    'data' => consolidadogasto::select('subtipogasto_id')
                                        ->where('tipogasto_id', $item2->tipogasto_id)
                                        ->where('reglote_id', $id_regLote)
                                        ->groupBy('subtipogasto_id')
                                        ->get()
                                        ->map(function ($item3) use ($id_regLote, $hect) {
                                            $totalGasto3 = consolidadogasto::where('subtipogasto_id', $item3->subtipogasto_id)
                                                ->where('reglote_id', $id_regLote)
                                                ->sum('total');
                                            $hect3 = $item3->first()->reglote->Hect;
                                            return [
                                                'nombre_subtipogasto' => $item3->subtipogasto->nombre,
                                                'gasto_total' => number_format($totalGasto3, 0, ',', '.'),
                                                'gastoxhect' => number_format($totalGasto3 / $hect, 0, ',', '.'),
                                            ];
                                        }),
                                ];
                            }),
                    ];
                }),
            'movcumplidos' => $this->getCumplidos($id_regLote),
            'consolidadoventas' => $this->getConsolidadoVentas($id_regLote),
            'mov_importados' => consolidadogasto::where('reglote_id', $id_regLote)->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'fecha' => $item->fecha,
                    'gasto_id' => $item->gasto_id,
                    'gasto' => $item->gasto->nombre,
                    'tipogasto' => $item->tipogasto->nombre,

                    'codigo' => $item->subtipogasto->codigo,
                    'subtipogasto' => $item->subtipogasto->nombre,
                    'cantidad' => $item->cantidad,
                    'cantidad_f' => number_format($item->cantidad, 2, ',', '.'),
                    'preciounitario' => $item->preciounitario,
                    'preciounitario_f' => number_format($item->preciounitario, 0, ',', '.'),
                    'total' => $item->total,
                    'total_f' => number_format($item->total, 0, ',', '.'),
                ];
            }),
            'gasto_general' => [
                'costoxhect' => [],
                'ppt' => ppt_detalle_costo::with('gasto')->where('finca_id', $regLote->finca_id)->get()->map(function ($item) {
                    return [
                        'nombre_gasto' => $item->gasto->nombre,
                        'costoxhect' =>  number_format($item->costoxhect, 0, ',', '.'),
                        'costoxhect_n' => $item->costoxhect,
                        'costoxhect_total' => number_format($item->sum('costoxhect'), 0, ',', '.'),
                    ];
                }),
            ],
        ];

        return json_encode($data);
    }

    public function get_mov_import($id_regLote)
    {

        consolidadogasto::where('reglote_id', $id_regLote)->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'gasto_id' => $item->gasto_id,
                'total' => $item->total,
                'fecha' => $item->fecha,
            ];
        });
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
                    'valor_unit' => number_format($item->valor_total / $item->cant, 0, ',', '.'),
                    'valor_total' => number_format($item->valor_total, 0, ',', '.'),
                    'obs' => $item->observaciones,
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
                    'valor_unit' => number_format($item->PrecioUnit, 0, ',', '.'),
                    'valor_total' => number_format($item->PrecioTotal, 0, ',', '.'),
                    'obs' => $item->CumlidoAplicacion->Observaciones,
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
                    'valor_unit' => number_format($item->ValorUnit, 0, ',', '.'),
                    'valor_total' => number_format($item->Total, 0, ',', '.'),
                    'obs' => $item->cumplidoordenservicio->Observaciones,
                ];
            });

        //  Record de visitas

        $recordvisita = ProductoRecordVisitas::with('RecordVisita')->select('RecordVisita_id', 'producto_id', 'Dosis_por_Ha', 'cantidad_Total', 'fecha_estimada_aplicacion')
            ->whereHas('RecordVisita', function ($query) use ($id_regLote) {
                $query->where('RegLote_id', $id_regLote);
            })
            ->get()
            ->map(function ($item) {
                return [
                    'fecha' => \Carbon\Carbon::parse($item->RecordVisita->fecha)->format('Y-m-d'),
                    'tipo' => "Record Agronomo",
                    'codigo' => $item->RecordVisita->Codigo,
                    'labor' => null,
                    'producto' => $item->Producto ? $item->Producto->MateriaPrima : null,
                    'cantidad' => $item->cantidad_Total,
                    'valor_unit' => number_format($item->PrecioUnit, 0, ',', '.'),
                    'valor_total' => number_format($item->PrecioTotal, 0, ',', '.'),
                    'obs' =>  $item->RecordVisita->diagnostico . " ; " . $item->RecordVisita->observaciones,
                ];
            });

        $cump_laborcampo_id = cumplido_laborcampodetallelote::where('reg_lote_id', $id_regLote)->select('cump_laborcampo_id')->get();

        $cumplidoLaborCampo = CumplidoLaboresCampo::with('labor', 'producto')->whereIn('id', $cump_laborcampo_id)->get()->map(function ($item) {
            return [
                'fecha' => \Carbon\Carbon::parse($item->fecha)->format('Y-m-d'),
                'tipo' => "Labor Campo",
                'codigo' => $item->codigo,
                'labor' => $item->labor->labor,
                'producto' => $item->producto ? $item->producto->MateriaPrima : null,
                'cantidad' => $item->cantidadtotal,
                'valor_unit' => number_format($item->PrecioUnit, 0, ',', '.'),
                'valor_total' => number_format($item->PrecioTotal, 0, ',', '.'),
                'obs' => $item->observaciones,
            ];
        });

        // Combinar y ordenar resultados
        $cumplidos = $cumplidosAplicacionProducto->merge($cumplidosMaquinaria)
            ->merge($cumplidosOrdenServicio)
            ->merge($recordvisita)
            ->merge($cumplidoLaborCampo)
            ->sortByDesc('fecha')
            ->values(); // Resetea los índices de la colección

        // Formatear los datos en un solo JSON


        return $cumplidos->sortByDesc('fecha');
    }

    public function getCumplidosapi()
    {
        $debugId = uniqid('getCumplidosapi_', true);
        $limit = (int) request()->query('limit', 1000);
        $limit = max(1, min($limit, 5000));
        $fechaInicio = Carbon::now()->startOfMonth()->subMonths(2)->startOfDay();
        $fechaFin = Carbon::today()->endOfDay();

        try {
            logger()->info('Inicio getCumplidosapi', [
                'debug_id' => $debugId,
                'limit' => $limit,
                'fecha_inicio' => $fechaInicio->toDateTimeString(),
                'fecha_fin' => $fechaFin->toDateTimeString(),
                'url' => request()->fullUrl(),
                'user_id' => optional(request()->user())->id,
            ]);

            $cumplidosAplicacionProducto = CumplidoAplicacionProducto::with('CumlidoAplicacion')->select('CumplidoAplicacion_id', 'producto_id', 'labor_id', 'cantidad_Total', 'PrecioTotal', 'PrecioUnit')
                ->whereHas('CumlidoAplicacion', function ($query) use ($fechaInicio, $fechaFin) {
                    $query->whereNotNull('labor_id')
                        ->whereBetween('fecha', [$fechaInicio, $fechaFin]);
                })
                ->orderByDesc('id')
                ->limit($limit)
                ->get()
                ->map(function ($item) use ($debugId) {
                    try {
                        $fecha = Carbon::parse($item->CumlidoAplicacion->fecha);

                        return [
                            //'fecha' => $item->CumlidoAplicacion->fecha,
                            '_fecha_orden' => $fecha->timestamp,
                            'fecha' => $fecha->format('d/m/Y'),
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
                    } catch (\Throwable $e) {
                        logger()->error('Error mapeando CumplidoAplicacionProducto en getCumplidosapi', [
                            'debug_id' => $debugId,
                            'cumplido_aplicacion_producto_id' => $item->id ?? null,
                            'cumplido_aplicacion_id' => $item->CumplidoAplicacion_id ?? null,
                            'message' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);

                        throw $e;
                    }
                });


            // Consulta para Cumplidos de Orden de Servicio
            $cumplidosOrdenServicio = CumplidoOrdenServicioDetalle::with('CumplidoOrdenServicio', 'ClasificacionCentroCosto', 'UnidadMedida')->select('CumplidoOrdenServicio_id', 'TipoCentroCosto_id', 'UnidadMedida_id', 'Interno', 'DestinoServicio', 'finca_id', 'RegLote_id', 'Lote_id', 'Labor_id', 'Cantidad', 'DetalleLabor', 'ValorUnit', 'Total')
                ->whereHas('CumplidoOrdenServicio', function ($query) use ($fechaInicio, $fechaFin) {
                    $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
                })
                ->orderByDesc('id')
                ->limit($limit)
                ->get()
                ->map(function ($item) use ($debugId) {
                    try {
                        $fecha = Carbon::parse($item->cumplidoordenservicio->fecha);

                        return [
                            '_fecha_orden' => $fecha->timestamp,
                            'fecha' => $fecha->format('d/m/Y'),

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
                            'potrero' => $item->potrero_id ? $item->potrero->potrero : null,
                            'cantidad' => $item->Cantidad,
                            'unidad_medida' => $item->UnidadMedida->UnidadMedida,
                            'valor_unit' => $item->ValorUnit,
                            'valor_total' => $item->Total,
                            'observaciones' => $item->cumplidoordenservicio->Observaciones,
                            'factura' => $item->cumplidoordenservicio->factura,
                            'fecha_cierre' => !is_null($item->cumplidoordenservicio->factura) ? (is_null($item->cumplidoordenservicio->fecha_cierre) ? null : \Carbon\Carbon::parse($item->cumplidoordenservicio->fecha_cierre)->format('d-m-Y')) : null,
                        ];
                    } catch (\Throwable $e) {
                        logger()->error('Error mapeando CumplidoOrdenServicioDetalle en getCumplidosapi', [
                            'debug_id' => $debugId,
                            'cumplido_orden_servicio_detalle_id' => $item->id ?? null,
                            'cumplido_orden_servicio_id' => $item->CumplidoOrdenServicio_id ?? null,
                            'message' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);

                        throw $e;
                    }
                });


            // Combinar y ordenar resultados
            $cumplidos = $cumplidosAplicacionProducto->merge($cumplidosOrdenServicio)
                ->sortByDesc('_fecha_orden')
                ->map(function ($item) {
                    unset($item['_fecha_orden']);
                    return $item;
                })
                ->values(); // Resetea los índices de la colección

            logger()->info('Fin getCumplidosapi', [
                'debug_id' => $debugId,
                'total_aplicacion' => $cumplidosAplicacionProducto->count(),
                'total_orden_servicio' => $cumplidosOrdenServicio->count(),
                'total_respuesta' => $cumplidos->count(),
            ]);

            return $cumplidos;
        } catch (\Throwable $e) {
            logger()->error('Error general en getCumplidosapi', [
                'debug_id' => $debugId,
                'url' => request()->fullUrl(),
                'user_id' => optional(request()->user())->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Error interno al consultar cumplidos.',
                'debug_id' => $debugId,
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
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

    public function CostoxLote_PPT($RegLote)
    {
        if ($RegLote) {
            $CostoMP = 0;
            $CostoServ = 0;
            $SubTotalPPT_MP = 1;
            $subTotalPPT_ServAg = 1;
            $recordLoteMP = 0;
            $recordLoteServAgro = 0;
            $recordSubtotal = 0;
            $subtotal = 1;
            $subtotalppt = 1;

            $gasto_MateriaPrima_id = gasto::where('nombre', 'Materia Prima')->first()->id;
            $gasto_ServiciosAgropecuarios_id = gasto::where('nombre', 'Servicios Agropecuarios')->first()->id;
            $reg = RegistroLotes::find($RegLote);

            return [
                'Distrito' => $reg->distrito->distrito,
                'Finca' => $reg->finca->finca,
                'Lote' => $reg->lote->lote,
                'Codigo' => $reg->Codigo,
                'Hect' => $reg->Hect,
                'ppt_costoxhect' => number_format(ppt_detalle_costo::where('finca_id', $reg->finca_id)->sum('costoxhect'), 0, ',', '.'),
                'datatable' => [
                    [
                        'concepto' => 'Costo',
                        'data' => [
                            'detallado' =>
                            [
                                [
                                    'id_lote' => $reg->id,
                                    'nombre_gasto' => 'Materia Prima',
                                    'costoxhectf' => number_format(consolidadogasto::where('reglote_id', $RegLote)->where('gasto_id', $gasto_MateriaPrima_id)->sum('total') / $reg->Hect, 0, ',', '.'),
                                    'costoxhect' => $CostoMP = consolidadogasto::where('reglote_id', $RegLote)->where('gasto_id', $gasto_MateriaPrima_id)->sum('total') / $reg->Hect,
                                ],
                                [
                                    'id_lote' => $reg->id,
                                    'nombre_gasto' => 'Servicios Agropecuarios',
                                    'costoxhectf' => number_format(consolidadogasto::where('reglote_id', $RegLote)->where('gasto_id', $gasto_ServiciosAgropecuarios_id)->sum('total') / $reg->Hect, 0, ',', '.'),
                                    'costoxhect' => $CostoServ =  consolidadogasto::where('reglote_id', $RegLote)->where('gasto_id', $gasto_ServiciosAgropecuarios_id)->sum('total') / $reg->Hect,
                                ],
                            ],

                            'costoxhect' => $costototal =  consolidadogasto::where('reglote_id', $reg->id)->sum('total') / $reg->Hect,
                            'costoxhectf' => number_format($costototal, 0, ',', '.'),
                        ],
                    ],
                    [
                        'concepto' => 'Sin Validar',
                        'data' => [
                            'detallado' => [
                                [
                                    'id_lote' => $reg->id,
                                    'nombre_gasto' => 'Materia Prima',
                                    'valicacionMP' => $validacionMP =  $this->ValidacionSinVerificar($reg->id, gasto::where('nombre', 'Materia Prima')->first()->id),
                                    'costoxhectf' => number_format($this->get_total_cump('Materia Prima', $validacionMP, $reg->id) / $reg->Hect, 0, ',', '.'),
                                    'costoxhect' => $SinValMP =  $this->get_total_cump('Materia Prima', $validacionMP, $reg->id) / $reg->Hect,
                                ],
                                [
                                    'id_lote' => $reg->id,
                                    'nombre_gasto' => 'Servicios Agropecuarios',
                                    'hect' => $reg->Hect,
                                    'valicacionServA' => $validacionServA =  $this->ValidacionSinVerificar($reg->id, gasto::where('nombre', 'Servicios Agropecuarios')->first()->id),
                                    'costoxhectf' => number_format(($this->get_total_cump('Servicios Agropecuarios', $validacionServA, $reg->id) / $reg->Hect), 0, ',', '.'),
                                    'costoxhect' => $SinValServAgr =  $this->get_total_cump('Servicios Agropecuarios', $validacionServA, $reg->id) / $reg->Hect,
                                ],
                            ],

                            'costoxhect' => $sinValSubtotal =  $SinValMP + $SinValServAgr,
                            'costoxhectf' => number_format($sinValSubtotal, 0, ',', '.'),

                        ],
                    ],
                    /**
                    [
                        'concepto' => 'Records Lote',
                        'data' => [
                            'detallado' => [
                                [
                                    'id_lote' => $reg->id,
                                    'nombre_gasto' => 'Materia Prima',
                                    'valicacionMP' => $validacionMP =  0,
                                    'costoxhectf' => number_format(0, 0, ',', '.'),
                                    'costoxhect' => $recordLoteMP = 0,
                                ],
                                [
                                    'id_lote' => $reg->id,
                                    'nombre_gasto' => 'Servicios Agropecuarios',
                                    'valicacionServA' => $validacionServA =  $this->ValidacionSinVerificar($reg->id, gasto::where('nombre', 'Servicios Agropecuarios')->first()->id),
                                    'costoxhectf' => number_format(0, 0, ',', '.'),
                                    'costoxhect' => $recordLoteServAgro = 0,
                                ],
                            ],
                            'costoxhect' => $recordSubtotal = ($recordLoteMP + $recordLoteServAgro),

                        ],
                    ],

                     */
                    [
                        'concepto' => 'Sub Total',
                        'data' => [
                            'detallado' => [
                                [
                                    'id_lote' => $reg->id,
                                    'nombre_gasto' => 'Materia Prima',
                                    'costoxhect' => $subtotalMP =  $recordLoteMP + $SinValMP + $CostoMP, // Subtotal materia Prima ( costos , sin validar, records)
                                    'costoxhectf' => number_format($subtotalMP, 0, ',', '.'), // Subtotal materia Prima ( costos , sin validar, records)
                                ],
                                [
                                    'id_lote' => $reg->id,
                                    'nombre_gasto' => 'Servicios Agropecuarios',
                                    'costoxhect' => $subtotalServAgro =  $recordLoteServAgro + $SinValServAgr + $CostoServ, // Subtotal materia Prima ( costos , sin validar, records)
                                    'costoxhectf' => number_format($subtotalServAgro, 0, ',', '.') // Subtotal materia Prima ( costos , sin validar, records)
                                ],
                            ],
                            'costoxhect' => $subtotal = $costototal + $recordSubtotal + $sinValSubtotal,
                            'costoxhectf' => number_format($subtotal, 0, ',', '.'),

                        ],
                    ],
                    [
                        'concepto' => 'Presupuesto',
                        'data' => [
                            'detallado' => ppt_detalle_costo::with('gasto')->where('finca_id', $reg->finca_id)->get()->map(function ($item) use ($reg, &$SubTotalPPT_MP, &$subTotalPPT_ServAg) {
                                return [
                                    'nombre_gasto' => $item->gasto->nombre,
                                    'costoxhectf' =>  number_format($item->costoxhect, 0, ',', '.'),
                                    'costoxhectmp' => $SubTotalPPT_MP = (($item->gasto->nombre == 'Materia Prima') ? $item->costoxhect : $SubTotalPPT_MP),
                                    'costoxhectserv' => $subTotalPPT_ServAg = ($item->gasto->nombre == 'Servicios Agropecuarios') ? $item->costoxhect : $subTotalPPT_ServAg,
                                    'total' => $item->costoxhect * $reg->Hect,
                                ];
                            }),
                            'costoxhect' => $subtotalppt = ppt_detalle_costo::where('finca_id', $reg->finca_id)->sum('costoxhect'),
                            'costoxhectf' => number_format($subtotalppt, 0, ',', '.'),
                        ],
                    ],

                    [

                        'concepto' => 'Ejecutado',
                        'data' => [
                            'detallado' => [
                                [
                                    'id_lote' => $reg->id,
                                    'nombre_gasto' => 'Materia Prima',
                                    'subtotalMP' => $subtotalMP,
                                    'subTotalPPT_MP' => $SubTotalPPT_MP,
                                    'costoxhectf' => number_format(($subtotalMP / $SubTotalPPT_MP) * 100, 2, ',', '.') . "%",
                                    'costoxhect' => $subtotalMP / $SubTotalPPT_MP,
                                ],
                                [
                                    'id_lote' => $reg->id,
                                    'nombre_gasto' => 'Servicios Agropecuarios',
                                    'subtotalServAgro' => $subtotalServAgro,
                                    'subTotalPPT_ServAg' => $subTotalPPT_ServAg,
                                    'costoxhectf' => number_format(($subtotalServAgro / $subTotalPPT_ServAg) * 100, 2, ',', '.') . "%",
                                    'costoxhect' => $subtotalServAgro / $subTotalPPT_ServAg,
                                ],
                            ],
                            'costoxhect' => $ejecutado = ($subtotalppt != 0) ? $subtotal / $subtotalppt : 0,
                            'costoxhectf' => number_format($ejecutado * 100, 2, ',', '.') . "%",
                        ],
                    ],




                ]


            ];
        }
    }

    public function ValidacionSinVerificar($reglote_id, $gasto_id)
    {


        $fecha_actual = Carbon::now();
        $mes_anterior = $fecha_actual->subMonth()->month;
        $anio_actual = $fecha_actual->year;

        $num_registros = consolidadogasto::whereMonth('fecha', $mes_anterior)
            ->whereYear('fecha', $anio_actual)
            ->where('reglote_id', $reglote_id)
            ->where('gasto_id', $gasto_id)
            ->count();


        return boolval($num_registros);
    }
    public function get_total_cump($tipogasto, $validacion, $reglote_id)
    {

        $data = 0;
        $CumplidoAplicacionProducto = 0;
        $cumplidosOrdenServicio = 0;
        if ($tipogasto == "Materia Prima") {
            $CumplidoAplicacionProducto = CumplidoAplicacionProducto::whereNotNull('producto_id')->whereNull('labor_id')
                ->whereHas('CumlidoAplicacion', function ($query) use ($reglote_id) {
                    $query->where('factura', null)
                        ->where('reg_lote_id', $reglote_id);
                })
                ->sum('PrecioTotal');
        } elseif ($tipogasto == "Servicios Agropecuarios") {
            $CumplidoAplicacionProducto = CumplidoAplicacionProducto::whereNotNull('labor_id')->whereNull('producto_id')
                ->whereHas('CumlidoAplicacion', function ($query) use ($reglote_id, $validacion) {
                    if ($validacion) {
                        $query->where('factura', null);
                    } else {
                        $query->where(function ($query) {
                            $query->where('fecha_cierre', null)
                                ->orWhereMonth('fecha_cierre', Carbon::now()->subMonth()->month)
                                ->orWhereMonth('fecha_cierre', Carbon::now()->month);
                        });
                    }

                    $query->where('reg_lote_id', $reglote_id);
                })
                ->sum('PrecioTotal');


            $cumplidosOrdenServicio = CumplidoOrdenServicioDetalle::where('RegLote_id', $reglote_id)->whereHas('CumplidoOrdenServicio', function ($query) use ($validacion) {
                if ($validacion) {
                    $query->where('fecha_cierre', null);
                } else {
                    $query->where(function ($query) {
                        $query->where('fecha_cierre', null)
                            ->orWhereMonth('fecha_cierre', Carbon::now()->subMonth()->month)
                            ->orWhereMonth('fecha_cierre', Carbon::now()->month);
                    });
                }
            })->sum('Total');
        }
        $data = $cumplidosOrdenServicio + $CumplidoAplicacionProducto;
        return $data;
    }

    public function get_record($id_regLote)
    {
        $CodigoRecord = CumplidoAplicacion::select('RecordVisita_id')->where('reg_lote_id', $id_regLote)->whereNotNull('RecordVisita_id')->pluck('RecordVisita_id');
        $record = RecordVisita::where('RegLote_id', $id_regLote)->whereNotIn('Codigo', $CodigoRecord)->get();
    }
}
