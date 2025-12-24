<?php

namespace App\Http\Controllers\Modulos\Cumplidos\CumplidoAplicacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modulos\Cumplidos\CumplidoAplicacion\StoreCumplidoAplicacionRequest;
use App\Http\Requests\Modulos\Cumplidos\CumplidoAplicacion\UpdateCumplidoAplicacionRequest;
use App\Models\contratista;
use App\Models\CumplidoAplicacion;
use App\Models\CumplidoAplicacionProducto;
use App\Models\Distrito;
use App\Models\Empleados;
use App\Models\Finca;
use App\Models\Grupo_MateriaPrima;
use App\Models\Labor;
use App\Models\Lote;
use App\Models\MateriaPrima;
use App\Models\ProductoRecordVisitas;
use App\Models\RecordVisita;
use App\Models\RegistroLotes;
use App\Models\zona;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use PhpOffice\PhpSpreadsheet\Spreadsheet; // Carga La Libreria Excel
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; // Exportar Excel

use function PHPUnit\Framework\isNull;

class CumplidoAplicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // Verificar permisos manualmente en el controlador
        if (! Gate::allows('mod.cump_aplicacion.show')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }



        $threeMonthsAgo = Carbon::now()->subMonths(3);

        $cumplidoAplicacion = CumplidoAplicacionProducto::with(['labor', 'CumlidoAplicacion' => function ($query) {
            $query->orderBy('fecha', 'desc');
            $query->orderBy('codigo', 'desc');
        }])
            ->whereNotNull('labor_id')
            ->whereHas('CumlidoAplicacion', function ($query) use ($threeMonthsAgo) {
                $query->where(function ($q) use ($threeMonthsAgo) {
                    $q->where('fecha', '>=', $threeMonthsAgo)
                        ->orWhereNull('factura');
                });
            })
            ->get()
            ->sortByDesc(function ($cumplidoAplicacion) {
                return $cumplidoAplicacion->CumlidoAplicacion->fecha;
            })
            ->values()
            ->map(function ($cumplidoAplicacion) {
                return [
                    'id' => $cumplidoAplicacion->CumlidoAplicacion->id,
                    'codigo' => $cumplidoAplicacion->CumlidoAplicacion->codigo,
                    'fecha' => Carbon::parse($cumplidoAplicacion->CumlidoAplicacion->fecha)->format('d/m/Y'),
                    'HectLote' => $cumplidoAplicacion->HectLote,
                    'distrito' => $cumplidoAplicacion->CumlidoAplicacion->distrito->distrito,
                    'finca' => $cumplidoAplicacion->CumlidoAplicacion->finca->finca,
                    'lote' => $cumplidoAplicacion->CumlidoAplicacion->lote->lote,
                    'fincalote' => $cumplidoAplicacion->CumlidoAplicacion->finca->finca . ' | ' . $cumplidoAplicacion->CumlidoAplicacion->lote->lote,
                    'codigo_lote' => $cumplidoAplicacion->CumlidoAplicacion->reg_lote->Codigo,
                    'SalidaAlmacen' => $cumplidoAplicacion->CumlidoAplicacion->CodSalidaAlmacen,
                    'ResponsableAplicacion_identificacion' => $cumplidoAplicacion->CumlidoAplicacion->ResponsableAplicacion->identificacion,
                    'ResponsableAplicacion_nombre' => $cumplidoAplicacion->CumlidoAplicacion->ResponsableAplicacion->nombre,
                    'Aplicacion' => $cumplidoAplicacion->labor->labor,
                    'PrecioUnit' => $cumplidoAplicacion->PrecioUnit,
                    'PrecioUnitFormat' => number_format($cumplidoAplicacion->PrecioUnit, 0, ',', '.'),
                    'cantidad' => $cumplidoAplicacion->cantidad_Total,
                    'Total' => $cumplidoAplicacion->PrecioTotal,
                    'TotalFormat' => number_format($cumplidoAplicacion->PrecioTotal, 0, ',', '.'),
                    'factura' => $cumplidoAplicacion->CumlidoAplicacion->factura,
                    'verificado' =>  $cumplidoAplicacion->CumlidoAplicacion->verificado ? true : false,
                    'observaciones' =>  $cumplidoAplicacion->CumlidoAplicacion->Observaciones,
                    'edit_url' => route('CumplidoAplicacion.edit', $cumplidoAplicacion->CumlidoAplicacion->slug),
                    'infrecord' => $record = RecordVisita::where('Codigo', $cumplidoAplicacion->CumlidoAplicacion->RecordVisita_id)->first(),
                    'record' => [
                        'Codigo' => $record ? $record->Codigo : null,
                        'fecha' => $record ? Carbon::parse($record->fecha)->format('d/m/Y') : null,
                        'finca' => $record ? $record->finca->finca : null,
                        'lote' => $record ? $record->lote->lote : null,
                        'fincalote' => $record ? $record->finca->finca . ' | ' . $record->lote->lote : null,
                        'diagnostico' => $record ? $record->diagnostico : null,
                        'observaciones' => $record ? $record->observaciones : null,
                    ]

                ];
            });

        // Consulta


        //Renderizar Vista


        return inertia::render('Modulos/Cumplidos/CumplidoAplicacion/Explorar', [
            'cumplidoAplicacion' => $cumplidoAplicacion,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        if (! Gate::allows('mod.cump_aplicacion.create')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        return Inertia::render('Modulos/Cumplidos/CumplidoAplicacion/Crear', [
            // Traer los datos del distrito para el auto completar
            'get_dataCod' => '',
            'get_finca' => Finca::all()->map(function ($reg) {
                return [
                    "id" => $reg->id,
                    "label" => $reg->finca
                ];
            }),
            'get_lote' => Lote::all()->map(function ($reg) {
                return [
                    "id" => $reg->id,
                    "label" => $reg->lote
                ];
            }),
            'get_empleado' => contratista::optionsContratista(),
            'get_labor' => Labor::optionsLabor([30, 31, 32, 33, 34, 35, 36, 39, 49, 66, 76, 102]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCumplidoAplicacionRequest $request)
    {
        //
        $fechaCumplido = empty($request->FechaCumplido) ? null :  Carbon::parse($request->FechaCumplido)->format('Y-m-d');
        $HoraInicio = empty($request->HoraInicio) ? null :   Carbon::parse($request->HoraInicio)->format('H:i:s');
        $HoraFin = empty($request->HoraFin) ? null :  Carbon::parse($request->HoraFin)->format('H:i:s');

        $CumplidoAplicacion = CumplidoAplicacion::create([
            'slug' => Str::slug($request->CodigoCumplido),
            'codigo' => $request->CodigoCumplido,
            'fecha' => empty($request->FechaCumplido) ? null :  Carbon::parse($request->FechaCumplido)->format('Y-m-d'),
            'HoraInicio' => $fechaCumplido . " " . $HoraInicio,
            'HoraFinal' => $fechaCumplido . " " . $HoraFin,
            'distrito_id' => Finca::where("id", $request->finca['id'])->first()->distrito_id,
            'finca_id' => $request->finca['id'],
            'lote_id' => $request->lote['id'],
            'reg_lote_id' => $request->RegLote_id,
            'HectLote' => $request->Hectareas,
            'RecordVisita_id' => $request->RecordVisita,
            'CodRecordVisita' => RecordVisita::where('Codigo', $request->RecordVisita)->first()->id ?? null,

            'CodSalidaAlmacen' => $request->SalidaAlmacen,
            'ResponsableAplicacion_id' => empty($request->ResponsableAplicacion) ? null : $request->ResponsableAplicacion['id'],
            'RiesgoLluvia' => $request->RiesgoLluvia,
            'Brisa' => $request->Brisa,
            'HumedadLote' => $request->Humedad,
            'Velocidad' => $request->Velocidad,
            'Seguridad' => $request->Seguridad,
            'EnpaquesEntregados' => $request->Empaques,
            'Observaciones' => $request->Observaciones,
            'Coordinador_id' => $request->Coordinador,
            'JefeCampo_id' => $request->JefeCampo,
        ]);


        /**
         * Adiciona los productos del record de visita
         */
        if (!isNull($request->RecordVisita) || !empty($request->RecordVisita)) {
            if (RecordVisita::where('Codigo', $request->RecordVisita)->first()) {
                $RecordVisita =  RecordVisita::where('Codigo', $request->RecordVisita)->firstOrFail();
                $ProductoRecordVisitas = ProductoRecordVisitas::where('RecordVisita_id', $RecordVisita->id)->get();
                foreach ($ProductoRecordVisitas as $productoRecordv) {
                    CumplidoAplicacionProducto::create([
                        'CumplidoAplicacion_id' => $CumplidoAplicacion->id,
                        'GrupoMateriaPrima_id' => $productoRecordv['GrupoMateriaPrima_id'],
                        'producto_id' => $productoRecordv['producto_id'],
                        'labor_id' => null,
                        'Dosis_por_Ha' => $productoRecordv['Dosis_por_Ha'],
                        'cantidad_Total' => $productoRecordv['cantidad_Total'],
                        'PrecioUnit' => MateriaPrima::find($productoRecordv['producto_id'])->PrecioUnit,
                        'PrecioTotal' => MateriaPrima::find($productoRecordv['producto_id'])->PrecioUnit  * $productoRecordv['cantidad_Total'],
                    ]);
                }
            }
        }


        /**
         * Adiciona los servicios , labores del cumplido de aplicacion
         */


        if (in_array($request->labor['id'], [30, 31, 32, 66])) { // Aplicacion Hectareas
            $cantidadTotal = doubleval($request->Hectareas);
            $precioTotal = doubleval($request->labor['CostoHect']) *  doubleval($request->Hectareas);
        } elseif (in_array($request->labor['id'], [33, 34, 35, 36, 39, 49, 76, 102])) { // Fertilizacion Bultos
            $cantidadTotal = CumplidoAplicacionProducto::where('CumplidoAplicacion_id', $CumplidoAplicacion->id)
                ->whereNotNull('producto_id')
                ->sum('cantidad_Total');
            $precioTotal = doubleval($request->labor['CostoHect']) * doubleval($cantidadTotal);
        }

        CumplidoAplicacionProducto::create([
            'CumplidoAplicacion_id' => $CumplidoAplicacion->id,
            'Dosis_por_Ha' => 1,
            'cantidad_Total' => $cantidadTotal,
            'labor_id' => $request->labor['id'],
            'PrecioUnit' => $request->labor['CostoHect'],
            'PrecioTotal' => $precioTotal,
            'GrupoMateriaPrima_id' => null,
            'MateriaPrima' => null,
        ]);

        $this->ActualizarValorLabor($CumplidoAplicacion->id);

        return redirect(route('CumplidoAplicacion.edit', $CumplidoAplicacion->slug))->with('success', 'Cumplido Aplicacion creado correctamente');
    }


    public function ActualizarValorLabor($CumplidoAplicacion_id)
    {
        $CumplidoAplicacionDetalle = CumplidoAplicacionProducto::where('CumplidoAplicacion_id', $CumplidoAplicacion_id)->whereNotNull('labor_id')->firstOrFail();
        $CumplidoAplicacion = CumplidoAplicacion::where('id', $CumplidoAplicacion_id)->firstOrFail();

        $Hectareas = $CumplidoAplicacion->HectLote;
        $PrecioUnit = Labor::where('id', $CumplidoAplicacionDetalle->labor_id)->firstOrFail()->CostoHect;


        /**
         * *Aplicacion
         */
        if (in_array($CumplidoAplicacionDetalle->labor_id, [30, 31, 32, 66])) { // Aplicacion
            $cantidadTotal = doubleval($Hectareas); // Las Cantidades de la Aplicacion es igual a las Hectareas
            /**
             * *Fertilizacion
             */
        } elseif (in_array($CumplidoAplicacionDetalle->labor_id, [33, 34, 35, 36, 39, 49, 76, 102])) { // Fertilizacion
            $cantidadTotal = CumplidoAplicacionProducto::where('CumplidoAplicacion_id', $CumplidoAplicacion_id)
                ->whereNotNull('producto_id')
                ->sum('cantidad_Total');
        }

        // Guarda y Actualiza la Labor.
        $CumplidoAplicacionDetalle->cantidad_Total = $cantidadTotal;
        $CumplidoAplicacionDetalle->PrecioUnit = $PrecioUnit;
        $CumplidoAplicacionDetalle->PrecioTotal = $cantidadTotal * $PrecioUnit;
        $CumplidoAplicacionDetalle->save();
    }
    /**
     * Display the specified resource.
     */
    public function show(CumplidoAplicacion $cumplidoAplicacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        // Verificar permisos manualmente en el controlador
        if (! Gate::allows('mod.cump_aplicacion.show')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        } else {
            // Consulta
            $CumplidoAplicacion = CumplidoAplicacion::where('slug', $slug)->firstOrFail();
            $CumplidoAplicacionLabor = null;


            if ($CumplidoAplicacionLabor = CumplidoAplicacionProducto::where('CumplidoAplicacion_id', $CumplidoAplicacion->id)->whereNotNull('labor_id')->first()) {



                if (in_array($CumplidoAplicacionLabor->labor_id, [30, 31, 32, 66, 102])) {
                    $optionGrupo_MateriaPrima =  Grupo_MateriaPrima::optionsGrupoMateriaPrima(null, [2, 3, 4, 5, 6, 7]);
                    $optionMP = MateriaPrima::optionsMateriaPrima([2, 3, 4, 5, 6, 7]);
                }
                if (in_array($CumplidoAplicacionLabor->labor_id, [33, 34, 35, 36, 39, 49, 76])) {
                    $optionGrupo_MateriaPrima =  Grupo_MateriaPrima::optionsGrupoMateriaPrima(null, [1, 7]);
                    $optionMP = MateriaPrima::optionsMateriaPrima([1, 7]);
                }
            } else {
                $CumplidoAplicacionLabor = null;
                $optionGrupo_MateriaPrima =  Grupo_MateriaPrima::optionsGrupoMateriaPrima(null, [2, 3, 4, 5, 6, 7]);
                $optionMP = MateriaPrima::optionsMateriaPrima([2, 3, 4, 5, 6, 7]);
            }



            //Renderizar Vista
            return Inertia::render('Modulos/Cumplidos/CumplidoAplicacion/Edit', [
                'datos' => [
                    'id' => $CumplidoAplicacion->id,
                    'slug' => $CumplidoAplicacion->slug,
                    'CodigoCumplido' => $CumplidoAplicacion->codigo,
                    'fecha' => $CumplidoAplicacion->fecha,
                    'HoraInicio' => empty($CumplidoAplicacion->HoraInicio) ? null :  $CumplidoAplicacion->HoraInicio,
                    'HoraFinal' => empty($CumplidoAplicacion->HoraFinal) ? null : $CumplidoAplicacion->HoraFinal,
                    'distrito_id' => $CumplidoAplicacion->distrito_id,
                    'finca_id' => Finca::optionsFinca(null, $CumplidoAplicacion->finca_id),
                    'lote_id' => Lote::optionsLote(null, $CumplidoAplicacion->lote_id),
                    'RegLote' => RegistroLotes::find($CumplidoAplicacion->reg_lote_id),
                    'HectLote' => $CumplidoAplicacion->HectLote,
                    'RecordVisita_id' => $CumplidoAplicacion->RecordVisita_id,
                    'CodRecordVisita' => $CumplidoAplicacion->CodRecordVisita,
                    'CodSalidaAlmacen' => $CumplidoAplicacion->CodSalidaAlmacen,
                    'ResponsableAplicacion_id' => contratista::optionsContratista($CumplidoAplicacion->ResponsableAplicacion_id),
                    'RiesgoLluvia' => $CumplidoAplicacion->RiesgoLluvia == 1 ? true : false,
                    'Brisa' => $CumplidoAplicacion->Brisa == 1 ? true : false,
                    'HumedadLote' => $CumplidoAplicacion->HumedadLote == 1 ? true : false,
                    'Velocidad' => $CumplidoAplicacion->Velocidad,
                    'Seguridad' => $CumplidoAplicacion->Seguridad == 1 ? true : false,
                    'EnpaquesEntregados' => $CumplidoAplicacion->EnpaquesEntregados == 1 ? true : false,
                    'Observaciones' => $CumplidoAplicacion->Observaciones,
                    'Coordinador_id' => Empleados::optionsEmpleadoPorCargo(null, $CumplidoAplicacion->Coordinador_id),
                    'JefeCampo_id' => Empleados::optionsEmpleadoPorCargo(null, $CumplidoAplicacion->JefeCampo_id),
                    'get_labor' => Labor::optionsLabor(null, null, $CumplidoAplicacionLabor->labor_id),

                ],
                'DataTableProductoCumplidoAplicacion' => CumplidoAplicacionProducto::with([
                    'Producto:id,MateriaPrima',
                    'grupoMPrima:id,GrupoMateriaPrima',
                ])->select(['id', 'slug', 'CumplidoAplicacion_id', 'labor_id', 'GrupoMateriaPrima_id', 'producto_id', 'Dosis_por_Ha', 'cantidad_Total', 'PrecioUnit', 'PrecioTotal'])->where('CumplidoAplicacion_id', $CumplidoAplicacion->id)->whereNull('labor_id')->get()->map(function ($reg) {

                    return [
                        'id' => $reg->id,
                        //'slug' => $reg->slug,
                        'CumplidoAplicacion_id' => $reg->CumplidoAplicacion_id,
                        'GrupoMateriaPrima_id' => $reg->GrupoMateriaPrima_id,
                        'producto_id' => $reg->producto_id,
                        'Dosis_por_Ha' => $reg->Dosis_por_Ha,
                        'cantidad_Total' => $reg->cantidad_Total,
                        'NombreProducto' => $reg->Producto ? $reg->Producto->MateriaPrima : $reg->Labor->labor,
                        'GrupoMateriaPrima' => $reg->grupoMPrima ? $reg->grupoMPrima->GrupoMateriaPrima : null,
                        'PrecioUnitFormat' => $reg->PrecioUnit,
                        'PrecioUnit' => '$ ' . number_format($reg->PrecioUnit, 2, ',', '.') . ' ',
                        'PrecioTotal' => $reg->PrecioTotal,
                        'PrecioTotalFormat' => '$ ' . number_format($reg->PrecioTotal, 2, ',', '.') . ' ',
                    ];
                }),

                'DataTableProductoCumplidoAplicacion_servicios' => CumplidoAplicacionProducto::with([
                    'Labor:id,labor'
                ])->select(['id', 'slug', 'CumplidoAplicacion_id', 'labor_id', 'GrupoMateriaPrima_id', 'producto_id', 'Dosis_por_Ha', 'cantidad_Total', 'PrecioUnit', 'PrecioTotal'])->where('CumplidoAplicacion_id', $CumplidoAplicacion->id)->whereNull('producto_id')->get()->map(function ($reg) {
                    return [
                        'id' => $reg->id,
                        //'slug' => $reg->slug,
                        'Labor' => $reg->Labor->labor,
                        'CumplidoAplicacion_id' => $reg->CumplidoAplicacion_id,
                        'Dosis_por_Ha' => $reg->Dosis_por_Ha,
                        'cantidad_Total' => $reg->cantidad_Total,
                        'PrecioUnitFormat' => $reg->PrecioUnit,
                        'PrecioUnit' => '$ ' . number_format($reg->PrecioUnit, 2, ',', '.') . ' ',
                        'PrecioTotal' => $reg->PrecioTotal,
                        'PrecioTotalFormat' => '$ ' . number_format($reg->PrecioTotal, 2, ',', '.') . ' ',
                    ];
                }),

                'options' => [
                    'get_finca' => Finca::optionsFinca(),
                    'get_lote' => Lote::optionsLote($CumplidoAplicacion->finca_id),
                    'get_empleados' => contratista::optionsContratista(),
                    'get_GrupoMPrima' => $optionGrupo_MateriaPrima,
                    'get_MPrima' => $optionMP,
                    'get_labor' => Labor::optionsLabor([30, 31, 32, 33, 34, 35, 36, 39, 49, 66, 76, 102],),
                    'get_labor_mezcla_abono' => Labor::optionsLabor([36]),
                ],

            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCumplidoAplicacionRequest $request, $CumplidoAplicacion)
    {
        //
        // Buscar el Grupo Materia Prima por Slug
        $CumplidoAplicacionModel = CumplidoAplicacion::where('slug', $CumplidoAplicacion)->firstOrFail();

        $fechaCumplido = empty($request->FechaCumplido) ? null :  Carbon::parse($request->FechaCumplido)->format('Y-m-d');
        $HoraInicio = empty($request->HoraInicio) ? null :   Carbon::parse($request->HoraInicio)->format('H:i:s');
        $HoraFin = empty($request->HoraFin) ? null :  Carbon::parse($request->HoraFin)->format('H:i:s');

        $CumplidoAplicacionModel->update([
            'slug' => Str::slug($request->CodigoCumplido),
            'codigo' => $request->CodigoCumplido,
            'fecha' => empty($request->FechaCumplido) ? null :  Carbon::parse($request->FechaCumplido)->format('Y-m-d'),
            'HoraInicio' => $fechaCumplido . " " . $HoraInicio,
            'HoraFinal' => $fechaCumplido . " " . $HoraFin,
            'distrito_id' => Finca::where("id", $request->finca['id'])->first()->distrito_id,
            'finca_id' => $request->finca['id'],
            'lote_id' => $request->lote['id'],
            'reg_lote_id' => $request->RegLote_id,
            'HectLote' => $request->Hectareas,
            'RecordVisita_id' => $request->RecordVisita,
            'CodRecordVisita' => $request->CodRecordVisita,
            'CodSalidaAlmacen' => $request->SalidaAlmacen,
            'ResponsableAplicacion_id' => empty($request->ResponsableAplicacion) ? null : $request->ResponsableAplicacion['id'],
            'RiesgoLluvia' => $request->RiesgoLluvia,
            'Brisa' => $request->Brisa,
            'HumedadLote' => $request->HumedadLote,
            'Velocidad' => $request->Velocidad,
            'Seguridad' => $request->Seguridad,
            'EnpaquesEntregados' => $request->EnpaquesEntregados,
            'Observaciones' => $request->Observaciones,
            'Coordinador_id' => $request->Coordinador,
            'JefeCampo_id' => $request->JefeCampo,
        ]);


        // Actualizar Labor
        CumplidoAplicacionProducto::where('CumplidoAplicacion_id', $CumplidoAplicacionModel->id)->whereNotNull('labor_id')->firstOrFail()->delete();

        if (in_array($request->labor['id'], [30, 31, 32, 102])) { // Aplicacion
            $costoxHect = $request->labor['CostoHect'];
            $cantidadTotal = doubleval($CumplidoAplicacionModel->HectLote);
            $precioTotal = doubleval($request->labor['CostoHect']) *  doubleval($CumplidoAplicacionModel->HectLote);

            CumplidoAplicacionProducto::create([
                'CumplidoAplicacion_id' => $CumplidoAplicacionModel->id,
                'Dosis_por_Ha' => 1,
                'cantidad_Total' => doubleval($CumplidoAplicacionModel->HectLote),
                'labor_id' => $request->labor['id'],
                'PrecioUnit' => $costoxHect,
                'PrecioTotal' => doubleval($precioTotal),
                'GrupoMateriaPrima_id' => null,
                'MateriaPrima' => null,
            ]);
        } elseif (in_array($request->labor['id'], [33, 34, 35, 36, 39])) { // Fertilizacion
            $cantidadTotal = CumplidoAplicacionProducto::where('CumplidoAplicacion_id', $CumplidoAplicacionModel->id)
                ->whereNotNull('producto_id')
                ->sum('cantidad_Total');
            $precioTotal = doubleval($request->labor['CostoHect']) * doubleval($cantidadTotal);

            CumplidoAplicacionProducto::create([
                'CumplidoAplicacion_id' => $CumplidoAplicacionModel->id,
                'Dosis_por_Ha' => 1,
                'cantidad_Total' => $cantidadTotal,
                'labor_id' => $request->labor['id'],
                'PrecioUnit' => $request->labor['CostoHect'],
                'PrecioTotal' => $precioTotal,
                'GrupoMateriaPrima_id' => null,
                'MateriaPrima' => null,
            ]);
        }

        $this->ActualizarValorLabor($CumplidoAplicacionModel->id);


        //return to_route('CumplidoAplicacion.edit', $CumplidoAplicacion);
        return redirect()->route('CumplidoAplicacion.edit', $CumplidoAplicacion)->with('success', 'Cumplido Aplicacion creado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cumplidoAplicacion)
    {
        //
        $cumplido =  CumplidoAplicacion::where('slug', $cumplidoAplicacion)->firstOrFail();

        CumplidoAplicacionProducto::where('CumplidoAplicacion_id', $cumplido->id)->delete();
        // Eliminar todos los registros ProductoRecordVisitas asociados a la RecordVisita
        $cumplido->delete();
        //CumplidoAplicacion::where('slug', $cumplidoAplicacion)->delete();

        return redirect(route('CumplidoAplicacion.Explorar'));
    }

    public function report()
    {
        // Verificar permisos manualmente en el controlador
        return inertia::render('Modulos/Cumplidos/CumplidoAplicacion/Exportar', []);
    }

    public function DataReport(Request $request)
    {
        $fecha_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');

        $cumplidoAplicacion = CumplidoAplicacionProducto::with(['labor', 'CumlidoAplicacion'])
            ->whereHas('CumlidoAplicacion', function ($query) use ($fecha_inicio, $fecha_fin) {
                $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            })
            ->whereNotNull('labor_id')


            ->get()
            ->map(function ($cumplidoAplicacion) {
                return [
                    'cumplido' => $cumplidoAplicacion->CumlidoAplicacion->codigo,
                    'fecha' => Carbon::parse($cumplidoAplicacion->CumlidoAplicacion->fecha)->format('d/m/Y'),
                    'distrito_id' => $cumplidoAplicacion->CumlidoAplicacion->distrito_id,
                    'distrito' => $cumplidoAplicacion->CumlidoAplicacion->distrito->distrito,
                    'finca_id' => $cumplidoAplicacion->CumlidoAplicacion->distrito->distrito,
                    'finca' => $cumplidoAplicacion->CumlidoAplicacion->finca->finca,
                    'zona' => $cumplidoAplicacion->CumlidoAplicacion->finca && $cumplidoAplicacion->CumlidoAplicacion->finca->zona ? $cumplidoAplicacion->CumlidoAplicacion->finca->zona->zona : null,

                    'lote_id' => $cumplidoAplicacion->CumlidoAplicacion->distrito->distrito,
                    'lote' => $cumplidoAplicacion->CumlidoAplicacion->lote->lote,
                    'codigo_lote' => $cumplidoAplicacion->CumlidoAplicacion->reg_lote->Codigo,
                    'Fecha_ini_Lote' => $cumplidoAplicacion->CumlidoAplicacion->reg_lote->FechaInicio,
                    'Fecha_Rec_Lote' => $cumplidoAplicacion->CumlidoAplicacion->reg_lote->FechaRecoleccion,
                    'HectLote' => $cumplidoAplicacion->CumlidoAplicacion->HectLote,
                    'RecordVisita' => $cumplidoAplicacion->CumlidoAplicacion->RecordVisita_id,
                    'SalidaAlmacen' => $cumplidoAplicacion->CumlidoAplicacion->CodSalidaAlmacen,
                    'velocidad' => $cumplidoAplicacion->CumlidoAplicacion->Velocidad,
                    'ResponsableAplicacion_identificacion' => $cumplidoAplicacion->CumlidoAplicacion->ResponsableAplicacion->identificacion,
                    'ResponsableAplicacion_nombre' => $cumplidoAplicacion->CumlidoAplicacion->ResponsableAplicacion->nombre,

                    'Aplicacion' => $cumplidoAplicacion->labor->labor,
                    'PrecioUnit' => $cumplidoAplicacion->PrecioUnit,
                    'cantidad' => $cumplidoAplicacion->cantidad_Total,
                    'bultos' =>  in_array($cumplidoAplicacion->labor->id, [33, 34, 35, 36, 39, 49, 76, 102]) ? $cumplidoAplicacion->cantidad_Total : 0,
                    'HectareasAplicacion' => in_array($cumplidoAplicacion->labor->id, [30, 31, 32, 66]) ? $cumplidoAplicacion->cantidad_Total : 0,
                    'Total' => $cumplidoAplicacion->PrecioTotal,
                    'observaciones' => $cumplidoAplicacion->CumlidoAplicacion->Observaciones,
                    'factura' => $cumplidoAplicacion->CumlidoAplicacion->factura,
                    'fecha_cierre' => $cumplidoAplicacion->CumlidoAplicacion->fecha_cierre,

                ];
            });


        return $cumplidoAplicacion;
    }

    public function Exportarxlsx(Request $request)
    {
        $CumplidoAplicacion =  collect($this->DataReport($request));
        // Crear un nuevo objeto Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados de las columnas
        $sheet->setCellValue('A1', 'Cumplido');
        $sheet->setCellValue('B1', 'Fecha Cumplido');
        $sheet->setCellValue('C1', 'Distrito');
        $sheet->setCellValue('D1', 'Zona');
        $sheet->setCellValue('E1', 'Finca');
        $sheet->setCellValue('F1', 'Lote');
        $sheet->setCellValue('G1', 'Codigo Lote');
        $sheet->setCellValue('H1', 'Fecha Ini Lote');
        $sheet->setCellValue('I1', 'Fecha Rec Lote');

        $sheet->setCellValue('J1', 'Hectareas Lote');
        $sheet->setCellValue('K1', 'Record Visita');
        $sheet->setCellValue('L1', 'Salida Almacen');
        $sheet->setCellValue('M1', 'Velocidad');
        $sheet->setCellValue('N1', 'Responsable Aplicacion Identificacion');
        $sheet->setCellValue('O1', 'Responsable Aplicacion Nombre');
        $sheet->setCellValue('P1', 'Aplicacion');
        $sheet->setCellValue('Q1', 'Precio Unit');
        $sheet->setCellValue('R1', 'Cantidad');
        $sheet->setCellValue('S1', 'Bultos');
        $sheet->setCellValue('T1', 'Hectareas Aplicadas');
        $sheet->setCellValue('U1', 'Total');
        $sheet->setCellValue('V1', 'Observaciones');
        $sheet->setCellValue('W1', 'factura');
        $sheet->setCellValue('X1', 'fecha_cierre');

        $styleFila1 = [
            'font' => [
                'bold' => true,
                'color' => [
                    'argb' => 'FFFFFF',
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '0D3161',
                ],
                'endColor' => [
                    'argb' => '0D3161',
                ],
            ]
        ];

        $styleFilas = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ]
            ],
        ];

        $spreadsheet->getActiveSheet()->getStyle('A1:X1')->applyFromArray($styleFila1);


        // Escribir los datos en el archivo Excel
        $row = 2;
        foreach ($CumplidoAplicacion as $cumplido) {
            $sheet->setCellValue('A' . $row, $cumplido['cumplido']);
            $sheet->setCellValue('B' . $row, $cumplido['fecha']);
            $sheet->setCellValue('C' . $row, $cumplido['distrito']);
            $sheet->setCellValue('D' . $row, $cumplido['zona']);
            $sheet->setCellValue('E' . $row, $cumplido['finca']);
            $sheet->setCellValue('F' . $row, $cumplido['lote']);
            $sheet->setCellValue('G' . $row, $cumplido['codigo_lote']);
            $sheet->setCellValue('H' . $row, $cumplido['Fecha_ini_Lote']);
            $sheet->setCellValue('I' . $row, $cumplido['Fecha_Rec_Lote']);
            $sheet->setCellValue('J' . $row, $cumplido['HectLote']);
            $sheet->setCellValue('K' . $row, $cumplido['RecordVisita']);
            $sheet->setCellValue('L' . $row, $cumplido['SalidaAlmacen']);
            $sheet->setCellValue('M' . $row, $cumplido['velocidad']);
            $sheet->setCellValue('N' . $row, $cumplido['ResponsableAplicacion_identificacion']);
            $sheet->setCellValue('O' . $row, $cumplido['ResponsableAplicacion_nombre']);
            $sheet->setCellValue('P' . $row, $cumplido['Aplicacion']);
            $sheet->setCellValue('Q' . $row, $cumplido['PrecioUnit']);
            $sheet->setCellValue('R' . $row, $cumplido['cantidad']);
            $sheet->setCellValue('S' . $row, $cumplido['bultos']);
            $sheet->setCellValue('T' . $row, $cumplido['HectareasAplicacion']);
            $sheet->setCellValue('U' . $row, $cumplido['Total']);
            $sheet->setCellValue('V' . $row, $cumplido['observaciones']);
            $sheet->setCellValue('W' . $row, $cumplido['factura']);
            $sheet->setCellValue('X' . $row, $cumplido['fecha_cierre']);

            $row++;
        }

        $spreadsheet->getActiveSheet()->getStyle('A2:X' . $row)->applyFromArray($styleFilas);


        // Configurar la respuesta para descargar el archivo
        $fileName = 'cumplidos_aplicacion.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/' . $fileName));

        // Descargar el archivo Excel
        return response()->download(storage_path('app/public/' . $fileName), $fileName, [
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ])->deleteFileAfterSend(true);
    }


    public function verificar(Request $request)
    {

        if (! Gate::allows('mod.cump_aplicacion.edit.verificar')) {
            abort(403, 'No tienes permiso para acceder.');
        }

        if (!is_null($request->selectedCumplidos) && !empty($request->selectedCumplidos)) {
            foreach ($request->selectedCumplidos as $row) {
                if (empty($request['factura'])) {
                    $CumplidoAplicacion =  CumplidoAplicacion::where('id', $row['id'])->update([
                        'factura' => null,  // Asignar el número de factura a este "cumplido"
                        'verificado' => false,
                        'fecha_cierre' => null,
                    ]);
                } else {
                    $CumplidoAplicacion =  CumplidoAplicacion::where('id', $row['id'])->update([
                        'factura' => $request['factura'],  // Asignar el número de factura a este "cumplido"
                        'verificado' => true,

                        'fecha_cierre' => Carbon::now()->format('Y/m/d'),
                    ]);
                }
            }
        } else {
            $CumplidoAplicacion =  CumplidoAplicacion::where('id', $request['cumplidoAplicacion'])->firstOrFail();

            if (empty($request['factura'])) {
                $CumplidoAplicacion->verificado =  false;
                $CumplidoAplicacion->factura = null;
                $CumplidoAplicacion->fecha_cierre = null;
            } else {
                $CumplidoAplicacion->verificado =  true;
                $CumplidoAplicacion->factura =  $request['factura'];
                $CumplidoAplicacion->fecha_cierre = Carbon::now()->format('Y/m/d');
            }
            $CumplidoAplicacion->save();
        }

        return redirect(route('CumplidoAplicacion.Explorar'))->with('success', 'Cumplido Aplicacion Verificado correctamente');
    }

    public function reporte()
    {
        $data = [
            'options' => [
                'zona' => zona::optionsZona(),
                'distrito' => Distrito::optionsDistrito(),
                'finca' => Finca::optionsFinca(),

            ]
        ];


        return inertia::render('Modulos/Cumplidos/CumplidoAplicacion/Reporte', $data);
    }

    public function DataReporte(Request $request)
    {
        return  [
            'CardTotales' => $this->DataReporte_Totales($request),
            'DataReporte_CantAplicacion' => $this->DataReporte_CantAplicacion($request),
        ];
    }


    public function DataReporte_Totales($request)
    {
        $cumplidoAplicacion = CumplidoAplicacionProducto::with(['labor', 'CumlidoAplicacion'])
            ->whereNotNull('labor_id');
        if (!is_null($request->fecha) && !empty($request->fecha)) {
            $fecha_inicio = Carbon::parse($request->fecha[0])->format('Y-m-d');
            $fecha_fin = Carbon::parse($request->fecha[1])->format('Y-m-d');
            $cumplidoAplicacion->whereHas('CumlidoAplicacion', function ($query) use ($fecha_inicio, $fecha_fin) {
                $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            });
        }

        if (!is_null($request->zona) && !empty($request->zona)) {
            $zonaIds = collect($request->zona)->pluck('id');
            $cumplidoAplicacion->whereHas('CumlidoAplicacion.finca', function ($query) use ($zonaIds) {
                $query->whereIn('zona_id', $zonaIds);
            });
        }
        // Agrupar por labor_id y sumar los valores de cantidad_Total y PrecioTotal
        $totalesPorLabor = $cumplidoAplicacion->selectRaw('labor_id, SUM(cantidad_Total) as totalCantidad, SUM(PrecioTotal) as totalPrecio')
            ->with('labor:id,labor')
            ->groupBy('labor_id')
            ->get()
            ->map(function ($item) {
                return [
                    'labor' => $item->labor->labor,
                    'totalCantidad' => number_format($item->totalCantidad, 0, ',', '.'),
                    'totalPrecio' => number_format($item->totalPrecio, 0, ',', '.'),
                ];
            });

        return $totalesPorLabor;
    }


    public function DataReporte_CantAplicacion(Request $request)
    {
        $cumplidoAplicacion = CumplidoAplicacionProducto::with(['labor', 'CumlidoAplicacion.finca'])
            ->whereNotNull('labor_id');

        if (!is_null($request->fecha) && !empty($request->fecha)) {
            $fecha_inicio = Carbon::parse($request->fecha[0])->format('Y-m-d');
            $fecha_fin = Carbon::parse($request->fecha[1])->format('Y-m-d');
            $cumplidoAplicacion->whereHas('CumlidoAplicacion', function ($query) use ($fecha_inicio, $fecha_fin) {
                $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            });
        }

        if (!is_null($request->zona) && !empty($request->zona)) {
            $zonaIds = collect($request->zona)->pluck('id');
            $cumplidoAplicacion->whereHas('CumlidoAplicacion.finca', function ($query) use ($zonaIds) {
                $query->whereIn('zona_id', $zonaIds);
            });
        }

        // Agrupar por año, mes y finca, y sumar los valores de cantidad_Total
        $totalesPorFinca = $cumplidoAplicacion->get()->groupBy(function ($item) {
            return Carbon::parse($item->CumlidoAplicacion->fecha)->format('Y') . '|' . Carbon::parse($item->CumlidoAplicacion->fecha)->format('m') . '|' . $item->CumlidoAplicacion->finca->finca;
        })->map(function ($items, $key) {
            list($year, $month, $finca) = explode('|', $key);
            $labores = $items->groupBy('labor.labor')->map(function ($laborItems) {
                return $laborItems->sum('cantidad_Total');
            });
            $meses = [
                '01' => 'Enero',
                '02' => 'Febrero',
                '03' => 'Marzo',
                '04' => 'Abril',
                '05' => 'Mayo',
                '06' => 'Junio',
                '07' => 'Julio',
                '08' => 'Agosto',
                '09' => 'Septiembre',
                '10' => 'Octubre',
                '11' => 'Noviembre',
                '12' => 'Diciembre'
            ];
            return [
                'finca' => $finca,
                'year' => $year,
                'month' => $meses[$month],
                'Aplicacion Dron' => $labores->get('Aplicacion Dron', 0),
                'Aplicacion Terrestre' => $labores->get('Aplicacion Terrestre', 0),
                'Fertilizacion Dirigida' => $labores->get('Fertilizacion Dirigida', 0),
                'Fertilizacion Terrestre' => $labores->get('Fertilizacion Terrestre', 0),
            ];
        })->values();

        return response()->json($totalesPorFinca);
    }

    public function APIDataReporte_CantAplicacion()
    {
        $cumplidoAplicacion = CumplidoAplicacionProducto::with(['labor', 'CumlidoAplicacion.finca.distrito', 'CumlidoAplicacion.finca.zona', 'CumlidoAplicacion.lote'])
            ->whereNotNull('labor_id');

        $cumplidos = $cumplidoAplicacion->get()->map(function ($item) {
            return [
                'codigo' => $item->CumlidoAplicacion->codigo,
                'fecha' => Carbon::parse($item->CumlidoAplicacion->fecha)->format('Y-m-d'),
                'finca' => $item->CumlidoAplicacion->finca->finca,
                'finca_id' => $item->CumlidoAplicacion->finca->id,
                'distrito' => $item->CumlidoAplicacion->finca && $item->CumlidoAplicacion->finca->distrito ? $item->CumlidoAplicacion->finca->distrito->distrito : null,
                'distrito_id' => $item->CumlidoAplicacion->finca && $item->CumlidoAplicacion->finca->distrito ? $item->CumlidoAplicacion->finca->distrito->id : null,
                'zona' => $item->CumlidoAplicacion->finca && $item->CumlidoAplicacion->finca->zona ? $item->CumlidoAplicacion->finca->zona->zona : null,
                'zona_id' => $item->CumlidoAplicacion->finca && $item->CumlidoAplicacion->finca->zona ? $item->CumlidoAplicacion->finca->zona->id : null,
                'codigo_lote' => $item->CumlidoAplicacion->reg_lote->Codigo,
                'codigo_lote_id' => $item->CumlidoAplicacion->reg_lote->id,
                'lote' => $item->CumlidoAplicacion->lote->lote,
                'lote_id' => $item->CumlidoAplicacion->lote->id,
                'contratista' => $item->CumlidoAplicacion->ResponsableAplicacion ? $item->CumlidoAplicacion->ResponsableAplicacion->nombre : null,
                'contratista_id' => $item->CumlidoAplicacion->ResponsableAplicacion ? $item->CumlidoAplicacion->ResponsableAplicacion->id : null,
                'labor' => $item->labor->labor,
                'labor_id' => $item->labor->id,
                'cantidad_total' => $item->cantidad_Total,
                'Cantidad_Hect' => in_array($item->labor->id, [30, 31, 32, 66]) ? $item->cantidad_Total : 0,
                'Cantidad_Bultos' => in_array($item->labor->id, [33, 34, 35, 36, 39, 102]) ? $item->cantidad_Total : 0,
                'precio_total' => $item->PrecioTotal,
            ];
        });

        return response()->json($cumplidos);
    }
}
