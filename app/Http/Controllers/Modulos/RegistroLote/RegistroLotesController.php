<?php

namespace App\Http\Controllers\Modulos\RegistroLote;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modulos\RegistroLote\StoreRegistroLotesRequest;
use App\Http\Requests\Modulos\RegistroLote\UpdateRegistroLotesRequest;
use App\Http\Resources\RegLote;
use App\Models\CumplidoAplicacion;
use App\Models\CumplidoAplicacionProducto;
use App\Models\CumplidoMaquinaria;
use App\Models\Distrito;
use App\Models\Finca;
use App\Models\Lote;
use App\Models\RegistroLotes;
use App\Models\status;
use App\Models\TipoCultivo;
use App\Models\TipoVariedad;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;
use TCPDF; // PDF
use PhpOffice\PhpSpreadsheet\Spreadsheet; // Carga La Libreria Excel
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; // Exportar Excel
use PhpOffice\PhpSpreadsheet\IOFactory; // Importar Excel
use Illuminate\Support\Facades\Gate;
use function PHPUnit\Framework\isNull;

class RegistroLotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */



    public function index()
    {
        // Validacion de Permisos
        if (! Gate::allows('mod.reglote.show')) {
            abort(403);
        }


        // Consulta
        $RegistroLotes = RegistroLotes::with(['status:id,status', 'Distrito:id,distrito', 'Finca:id,finca', 'TipoCultivo:id,TipoCultivo', 'TipoVariedad:id,TipoVariedad'])->select(['id', 'Hect', 'Codigo', 'NombreLote', 'FechaInicio', 'FechaSiembra', 'FechaGerminacion', 'FechaRecoleccion', 'FechaVenta', 'finca_id', 'distrito_id', 'TipoCultivo_id', 'TipoVariedad_id', 'status_id', 'slug'])->orderBy('status_id', 'asc')->orderBy('id', 'desc')->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'statusLote1' =>  $data->status->status,
                'statusLote' =>  function () use ($data) {
                    switch ($data->status->status) {
                        case 'Activo':
                            return [
                                'name' => 'Activo',
                                'severity' => 'success',
                            ];
                        case 'Cerrado':
                            return [
                                'name' => 'Cerrado',
                                'severity' => 'danger',
                            ];

                        default:
                            return [
                                'name' => "Sin Estado",
                                'severity' => 'info',
                            ];
                    }
                },

                'Status' => function () use ($data) {
                    switch (true) {
                        case $data->FechaVenta:
                            return [
                                'name' => 'Venta',
                                'severity' => 'danger',
                            ];
                        case $data->FechaRecoleccion:
                            return [
                                'name' => 'Recolectado',
                                'severity' => 'success',
                            ];
                        case $data->FechaGerminacion:
                            $diff = Carbon::parse($data->FechaGerminacion)->diffInDays(Carbon::now());

                            if ($diff >= 110 && $diff <= 120) {
                                return [
                                    'name' => 'Cosecha',
                                    'severity' => 'primary',
                                    'diff' => $diff
                                ];
                            } else if ($diff >= 100 && $diff <= 109) {
                                return [
                                    'name' => 'Maduracion',
                                    'severity' => 'primary',
                                    'diff' => $diff

                                ];
                            } else if ($diff >= 90 && $diff <= 99) {
                                return [
                                    'name' => 'Llenado',
                                    'severity' => 'primary',
                                    'diff' => $diff

                                ];
                            } else if ($diff >= 60 && $diff <= 89) {
                                return [
                                    'name' => 'Floracion',
                                    'severity' => 'primary',
                                    'diff' => $diff

                                ];
                            } else if ($diff >= 50 && $diff <= 59) {
                                return [
                                    'name' => 'Antesis',
                                    'severity' => 'primary',
                                    'diff' => $diff

                                ];
                            } else if ($diff >= 35 && $diff <= 49) {
                                return [
                                    'name' => 'Primordio',
                                    'severity' => 'primary',
                                    'diff' => $diff

                                ];
                            } else if ($diff >= 10 && $diff <= 34) {
                                return [
                                    'name' => 'Maximo Macollamiento',
                                    'severity' => 'success',
                                    'diff' => $diff

                                ];
                            } else if ($diff >= 1 && $diff <= 9) {
                                return [
                                    'name' => 'Plantula',
                                    'severity' => 'info',
                                    'diff' => $diff

                                ];
                            } else {
                                return [
                                    'name' => 'Crecimiento y Cuidado',
                                    'severity' => 'warm',
                                    'diff' => $diff

                                ];
                            }

                        case $data->FechaSiembra:
                            return [
                                'name' => 'Siembra',
                                'severity' => 'info',
                            ];
                        case $data->FechaInicio:
                            return [
                                'name' => 'Preparacion Terreno',
                                'severity' => 'secondary',
                            ];
                        default:
                            return 'Sin Estatus';
                    }
                },
                'Codigo' => $data->Codigo,
                'distrito_id' => $data->Distrito->distrito,
                'finca_id' => $data->Finca->finca,
                'NombreLote' => $data->NombreLote,
                'TipoVariedad_id' => is_null($data->TipoVariedad) ? "" : $data->TipoVariedad->TipoVariedad,
                'TipoCultivo_id' => is_null($data->TipoCultivo) ? "" : $data->TipoCultivo->TipoCultivo,
                'FechaSiembra' => $data->FechaSiembra,
                'FechaRecoleccion' => $data->FechaRecoleccion,
                'Hect' => $data->Hect,
                'slug' => $data->slug,
                'edit_url' => route('RegLote.edit', $data->slug),
                'RegistroLote_url' => route('RegLote.RegistroLote', $data->slug),
            ];
        });



        //Renderizar Vista
        return Inertia::render('Modulos/RegistroLote/Explorar', [
            'RegistroLotes' => $RegistroLotes,
            'create_url' => route('RegLote.Crear'),
            'options' => [
                'get_distrito' => Distrito::all()->map(function ($reg) {
                    return [
                        "id" => $reg->id,
                        "label" => $reg->distrito
                    ];
                }),
                'get_finca' => Finca::all()->map(function ($reg) {
                    return [
                        "id" => $reg->id,
                        "label" => $reg->finca
                    ];
                }),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Validacion de Permisos
        if (! Gate::allows('mod.reglote.create')) {
            abort(403);
        }

        //
        return Inertia::render('Modulos/RegistroLote/Crear', [
            // Traer los datos del distrito para el auto completar
            'get_dataCod' => '',
            'get_distrito' => Distrito::all()->map(function ($reg) {
                return [
                    "id" => $reg->id,
                    "label" => $reg->distrito
                ];
            }),
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
            'get_tipocultivo' => TipoCultivo::all()->map(function ($reg) {
                return [
                    "id" => $reg->id,
                    "label" => $reg->TipoCultivo
                ];
            }),
            'get_tipovariedad' => TipoVariedad::all()->map(function ($reg) {
                return [
                    "id" => $reg->id,
                    "label" => $reg->TipoVariedad
                ];
            }),
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegistroLotesRequest $request)
    {
        //
        $RegistroLotes = new RegistroLotes;
        $RegistroLotes->Codigo = $request->Codigo;
        $RegistroLotes->NombreLote = Str::trim(Str::squish(Str::upper($request->NombreLote)));
        $RegistroLotes->Hect = Lote::find($request->lote['id'])->hect;
        $RegistroLotes->status_id = status::where("status", "Activo")->first()->id;
        $RegistroLotes->FechaInicio = empty($request->FechaInicio) ? null :  Carbon::parse($request->FechaInicio)->format('Y-m-d');
        $RegistroLotes->FechaSiembra = empty($request->FechaSiembra) ? null : Carbon::parse($request->FechaSiembra)->format('Y-m-d');
        $RegistroLotes->FechaRecoleccion = empty($request->FechaRecoleccion) ? null : Carbon::parse($request->FechaRecoleccion)->format('Y-m-d');
        $RegistroLotes->FechaGerminacion = empty($request->FechaGerminacion) ? null : Carbon::parse($request->FechaGerminacion)->format('Y-m-d');
        $RegistroLotes->TipoCultivo_id = empty($request->TipoCultivo) ? null : $request->TipoCultivo['id'];
        $RegistroLotes->TipoVariedad_id = empty($request->TipoVariedad) ? null : $request->TipoVariedad['id'];
        $RegistroLotes->lote_id = $request->lote['id'];
        $RegistroLotes->finca_id = $request->finca['id'];
        $RegistroLotes->distrito_id = $request->distrito['id'];
        $RegistroLotes->save();

        return redirect(route('RegLote.Explorar')); // Redireccionar vista Explorar
    }

    /**
     * Display the specified resource.
     */
    public function show(RegistroLotes $registroLotes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        // Validacion de Permisos
        if (! Gate::allows('mod.reglote.show')) {
            abort(403);
        }
        // Consulta
        $RegistroLotes = RegistroLotes::where('slug', $slug)->firstOrFail();
        //Renderizar Vista


        return Inertia::render('Modulos/RegistroLote/Edit', [
            'datos' => [
                'id' => $RegistroLotes->id,
                'distrito' => Distrito::optionsDistrito($RegistroLotes->distrito_id),
                'finca' => empty($RegistroLotes->finca_id) ? null :  Finca::optionsFinca($RegistroLotes->distrito_id, $RegistroLotes->finca_id),
                'lote' =>  empty($RegistroLotes->lote_id) ? null : Lote::optionsLote($RegistroLotes->finca_id, $RegistroLotes->lote_id),
                'TipoCultivo' => empty($RegistroLotes->TipoCultivo_id) ? null : TipoCultivo::optionsTipoCultivo($RegistroLotes->TipoCultivo_id),
                'TipoVariedad' =>  empty($RegistroLotes->TipoVariedad_id) ? null : TipoVariedad::optionsTipoVariedad($RegistroLotes->TipoVariedad_id),

                'status' => [
                    'id' => $RegistroLotes->status_id,
                    'label' => status::where("id", $RegistroLotes->status_id)->firstOrFail()->status
                ],
                'FechaInicio' => (new DateTime($RegistroLotes->FechaInicio))->format('Y-m-d'),
                'FechaSiembra' => $RegistroLotes->FechaSiembra,
                'FechaGerminacion' => $RegistroLotes->FechaGerminacion,
                'FechaVenta' => $RegistroLotes->FechaVenta,
                'FechaRecoleccion' => $RegistroLotes->FechaRecoleccion,
                'Codigo' => $RegistroLotes->Codigo,
                'NombreLote' => $RegistroLotes->NombreLote,
                'hect' => $RegistroLotes->Hect,
                'slug' => $RegistroLotes->slug,
                'update_url' => route('Lote.update', $RegistroLotes->slug),
                'delete_url' => route('Lote.delete', $RegistroLotes->slug),
            ],
            // Traer los datos del distrito para el auto completar
            'get_distrito' => Distrito::optionsDistrito(),
            'get_finca' => Finca::optionsFinca(),
            'get_lote' => Lote::optionsLote($RegistroLotes->finca_id),
            'get_tipocultivo' => TipoCultivo::optionsTipoCultivo(),
            'get_tipovariedad' => TipoVariedad::optionsTipoVariedad(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegistroLotesRequest $request, $slug)
    {

        // Actualizar
        $RegistroLotes = RegistroLotes::where('slug', $slug)->firstOrFail();
        $RegistroLotes->Codigo = $request->Codigo;
        $RegistroLotes->NombreLote = Str::trim(Str::squish(Str::upper($request->NombreLote)));
        $RegistroLotes->Hect = $request->hect;
        $RegistroLotes->FechaInicio = empty($request->FechaInicio) ? null :  Carbon::parse($request->FechaInicio)->format('Y-m-d');
        $RegistroLotes->FechaSiembra = empty($request->FechaSiembra) ? null : Carbon::parse($request->FechaSiembra)->format('Y-m-d');
        $RegistroLotes->FechaGerminacion = empty($request->FechaGerminacion) ? null : Carbon::parse($request->FechaGerminacion)->format('Y-m-d');
        $RegistroLotes->FechaRecoleccion = empty($request->FechaRecoleccion) ? null : Carbon::parse($request->FechaRecoleccion)->format('Y-m-d');
        $RegistroLotes->FechaVenta = empty($request->FechaVenta) ? null : Carbon::parse($request->FechaVenta)->format('Y-m-d');
        $RegistroLotes->TipoCultivo_id = empty($request->TipoCultivo) ? null : $request->TipoCultivo['id'];
        $RegistroLotes->TipoVariedad_id = empty($request->TipoVariedad) ? null : $request->TipoVariedad['id'];
        $RegistroLotes->lote_id =  empty($request->lote) ? null :  $request->lote['id'];
        $RegistroLotes->finca_id =  empty($request->finca) ? null :  $request->finca['id'];
        $RegistroLotes->distrito_id = $request->distrito['id'];

        if (is_null($RegistroLotes->FechaRecoleccion)) {
            $RegistroLotes->status_id = 1;
        } elseif (!is_null($RegistroLotes->FechaRecoleccion) && is_null($RegistroLotes->FechaVenta)) {
            $RegistroLotes->status_id = 2;
        } elseif (!is_null($RegistroLotes->FechaRecoleccion) && !is_null($RegistroLotes->FechaVenta)) {
            $RegistroLotes->status_id = 3;
        }

        if (Gate::allows('mod.reglote.edit.status', $request)) {
            $RegistroLotes->status_id = $request->status['id'];
        }
        $RegistroLotes->save();

        return redirect(route('RegLote.edit', $RegistroLotes->slug));
    }

    public function store_auto(StoreRegistroLotesRequest $request)
    {
        //

        $RegLotesActivos = RegistroLotes::where('lote_id', $request->lote['id'])
            ->whereNull('FechaRecoleccion')
            ->get();

        if ($RegLotesActivos->count() > 0) {
            return redirect(route('RegLote.edit', $request->slug))->withErrors(['error' => 'Ya existe un lote activo en este lote.']);
        } else {
            $RegistroLotes = new RegistroLotes;
            $year = date('Y');
            $month = date('m');
            $ultimoCodigo = RegistroLotes::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->orderBy('created_at', 'desc')
                ->value('Codigo');
            $numero = 1;
            if ($ultimoCodigo) {
                $numero = (int) substr($ultimoCodigo, strrpos($ultimoCodigo, '_') + 1) + 1;
            }
            $RegistroLotes->Codigo = "PDT-{$year}{$month}_{$numero}";
            $RegistroLotes->NombreLote = Str::trim(Str::squish(Str::upper($request->NombreLote)));
            $RegistroLotes->Hect = $request->hect;
            $RegistroLotes->status_id = status::where("status", "Activo")->first()->id;
            $RegistroLotes->FechaInicio = empty($request->FechaRecoleccion) ? null :  Carbon::parse($request->FechaRecoleccion)->format('Y-m-d');
            $RegistroLotes->lote_id = $request->lote['id'];
            $RegistroLotes->finca_id = $request->finca['id'];
            $RegistroLotes->distrito_id = $request->distrito['id'];
            $RegistroLotes->save();
            return redirect(route('RegLote.edit', $RegistroLotes->slug));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        //
        $RegistroLotes =  RegistroLotes::where('slug', $slug)->firstOrFail();
        $RegistroLotes->delete();
        return redirect(route('RegLote.Explorar'));
    }


    /**
     * Hoja de Vida , Registro total del Lote
     */

    public function RegistroLote($slug)
    {
        // Consulta
        $RegistroLotes = RegistroLotes::where('slug', $slug)->firstOrFail();
        //Renderizar Vista

        $fechaSiembra = new DateTime($RegistroLotes->FechaSiembra);
        $fechaInicio = new DateTime($RegistroLotes->FechaInicio);
        $fechaCultivo = new DateTime($RegistroLotes->FechaRecoleccion);
        $fechaHoy = new DateTime(date("Y-m-d"));
        return Inertia::render('Modulos/RegistroLote/RegistroLote', [
            'datos' => [
                'id' => $RegistroLotes->id,
                "distrito" => Distrito::where("id", $RegistroLotes->distrito_id)->first()->distrito,
                'finca' => Finca::where("id", $RegistroLotes->finca_id)->first()->finca, //
                'lote' => Lote::where("id", $RegistroLotes->lote_id)->first()->lote, //
                'TipoCultivo' => isNull(TipoCultivo::where("id", $RegistroLotes->TipoCultivo_id)) ? "" : TipoCultivo::where("id", $RegistroLotes->TipoCultivo_id)->first()->TipoCultivo, //
                'TipoVariedad' => isNull(TipoVariedad::where("id", $RegistroLotes->TipoVariedad_id)) ? "" : TipoVariedad::where("id", $RegistroLotes->TipoVariedad_id)->first()->TipoVariedad, //

                'FechaInicio' =>  $RegistroLotes->FechaInicio,
                'DiasPreparacion' => empty($RegistroLotes->FechaSiembra) ? $fechaInicio->diff($fechaHoy)->days : $fechaInicio->diff($fechaSiembra)->days,
                'FechaSiembra' => $RegistroLotes->FechaSiembra,
                'FechaGerminacion' => $RegistroLotes->FechaGerminacion,
                'DiasCultivo' => empty($RegistroLotes->FechaRecoleccion) ? $fechaSiembra->diff($fechaHoy)->days : $fechaSiembra->diff($fechaCultivo)->days,
                'FechaRecoleccion' => $RegistroLotes->FechaRecoleccion,
                'Codigo' => $RegistroLotes->Codigo,
                'NombreLote' => $RegistroLotes->NombreLote,
                'hect' => $RegistroLotes->Hect,

                "CumplidoMaquinaria" => CumplidoMaquinaria::where('RegLote_id', $RegistroLotes->id)->get()->map(function ($data) {
                    return [
                        'id' => $data->id,
                        'codigo' => $data->Codigo,
                        'fecha' => $data->fecha,
                        'operario' => $data->Empleados->nombre1,
                        'labor' => $data->Labor->labor,
                        'maquinaria' => $data->Maquina->CodMaq,
                        'cantidad' => $data->cant,
                        'valor_unit' => $data->valor_unit,
                        'valor_total' => '$' . number_format($data->valor_total, 0, ',', '.'),
                        'valor_total_sinformato' => $data->valor_total,
                        'edit_url' => route('CumpMaquinaria.edit', $data->slug),
                    ];
                }),
                "CumplidoAplicacion" => CumplidoAplicacionProducto::all(),
                'slug' => $RegistroLotes->slug,
                'update_url' => route('Lote.update', $RegistroLotes->slug),
                'delete_url' => route('Lote.delete', $RegistroLotes->slug),
            ],
            // Traer los datos del distrito para el auto completar

        ]);
    }

    /**Options */

    public function getOptionsRegLote(Request $request)
    {
        $status = [status::where("status", "Activo")->first()->id, status::where("status", "Bloqueado")->first()->id, status::where("status", "Cerrado")->first()->id];
        if ($request->has('status') && !is_null($request->status)) {
            if ($request->status['id'] == 1) {
                // Buscar Activo y Bloqueado
                $status = [status::where("status", "Activo")->first()->id, status::where("status", "Bloqueado")->first()->id];
            } elseif ($request->status['id'] == 2) {
                // Buscar Cerrado
                $status = [status::where("status", "Cerrado")->first()->id];
            } elseif ($request->status['id'] == 3) {
                // Buscar todos los estados
                // No se necesita agregar ninguna condición adicional
                $status = [status::where("status", "Activo")->first()->id, status::where("status", "Bloqueado")->first()->id, status::where("status", "Cerrado")->first()->id];
            }
        } else {
            $status = [status::where("status", "Activo")->first()->id, status::where("status", "Bloqueado")->first()->id, status::where("status", "Cerrado")->first()->id];
        }

        $lote_id = $request->has('lote') ? ($request->lote ? $request->lote['id'] : null) : null;

        $RegLoteId = $request->has('RegLote') ? $request->RegLote : null;

        return RegistroLotes::optionsRegLote($status, $lote_id, $RegLoteId);
    }

    public function getOptionsRegLoteActivo(Request $request)
    {

        $data = RegistroLotes::select(['id', 'Codigo', 'NombreLote', 'Hect',  'slug'])->where('lote_id',  $request['id'])->whereNull('FechaRecoleccion')->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'label' => $data->Codigo . " | " . $data->NombreLote . " | " . $data->Hect,
                'Codigo' => $data->Codigo,
                'Hect' => $data->Hect,
                'NombreLote' => $data->NombreLote,
            ];
        });
        return response()->json($data);
    }

    public function getRegLoteActivo(Request $request)
    {
        $data = RegistroLotes::select(['id', 'Codigo', 'FechaInicio', 'FechaGerminacion', 'NombreLote', 'Hect',  'slug'])->where('lote_id',  $request['id'])->where('status_id', 1)->get();

        if ($data->count() == 1) {
            return $data->map(function ($data) {
                return [
                    'id' => $data->id,
                    'Codigo' => $data->Codigo,
                    'NombreLote' => $data->NombreLote,
                    'FechaInicio' => $data->FechaInicio,
                    'FechaGerminacion' => $data->FechaGerminacion,
                    'Hect' => $data->Hect,
                ];
            })->first();
        } else if ($data->count() > 1) {
            return false;
        } else {
            return null;
        }
    }

    /**
     * Traer Hectareas Del Lote Del select de la vista de Cumplidos de Maquinaria
     */
    public function getHectLote(Request $request)
    {

        if (!empty($request)) {
            $data = RegistroLotes::select(['id', 'Hect'])->where('id',  $request['id'])->get()->firstOrFail()->Hect;
        } else {
            $data = 0;
        }

        return response()->json($data);
    }

    public function get_data_RegLote(Request $request)
    {
        // Consulta
        $count = RegistroLotes::where('lote_id', $request->id)->where('status_id', 1)->count();

        if ($count) {
            $Validacion = false;
        } else {
            $Validacion = true;
        }
        //$Validacion = $count;

        $RegistroLote = RegistroLotes::with(['status:id,status', 'Distrito:id,distrito', 'Finca:id,finca', 'TipoCultivo:id,TipoCultivo', 'TipoVariedad:id,TipoVariedad'])->select(['id', 'Hect', 'Codigo', 'NombreLote', 'FechaInicio', 'FechaSiembra', 'FechaGerminacion', 'FechaRecoleccion', 'FechaVenta', 'finca_id', 'distrito_id', 'TipoCultivo_id', 'TipoVariedad_id', 'status_id', 'slug'])->where('lote_id', $request->id)->orderBy('status_id', 'asc')->orderBy('id', 'desc')->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'statusLote1' =>  $data->status->status,
                'Codigo' => $data->Codigo,
                'distrito_id' => $data->Distrito->distrito,
                'finca_id' => $data->Finca->finca,
                'NombreLote' => $data->NombreLote,
                'FechaSiembra' => $data->FechaSiembra,
                'FechaGerminacion' => $data->FechaGerminacion,
                'FechaRecoleccion' => $data->FechaRecoleccion,
                'Hect' => $data->Hect,
                'slug' => $data->slug,
                'edit_url' => route('RegLote.edit', $data->slug),
                'RegistroLote_url' => route('RegLote.RegistroLote', $data->slug),
            ];
        });


        return response()->json(['RegistroLote' => $RegistroLote, 'Validacion' => $Validacion]);
    }


    /**
     * Exportar PDF
     */
    public function exportPDF($slug)
    {
        // Consulta
        $RegistroLotes = RegistroLotes::where('slug', $slug)->firstOrFail();
        // Crear una nueva instancia de TCPDF
        $pdf = new TCPDF();
        // Establecer el título y el autor
        $pdf->SetTitle('Ejemplo de PDF con TCPDF');
        $pdf->SetAuthor('Nombre del autor');
        // Agregar una página
        $pdf->AddPage();
        // Escribir el contenido HTML
        $html = '<h1>Lote ' . $RegistroLotes->Codigo . '</h1><p>Contenido HTML convertido a PDF.</p>';
        $pdf->writeHTML($html, true, false, true, false, '');
        // Obtener el contenido del PDF como una cadena de bytes
        $pdfContent = $pdf->Output('example.pdf', 'S');
        // Configurar la respuesta HTTP
        $response = response($pdfContent, 200);
        // Configurar los encabezados para forzar la descarga o visualización en una nueva pestaña
        $response->header('Content-Type', 'application/pdf');
        $response->header('Content-Disposition', 'attachment; filename="example.pdf"'); // Para forzar la descarga
        // Devolver la respuesta
        return $response;

        exit;
    }

    /**
     * Exportar Excel
     */
    public function exportXLSX($slug)
    {
        // Obtener datos de la base de datos
        $registroLotes = RegistroLotes::with(['Distrito:id,distrito', 'Finca:id,finca', 'TipoCultivo:id,TipoCultivo', 'TipoVariedad:id,TipoVariedad'])
            ->select(['id', 'Hect', 'Codigo', 'NombreLote', 'FechaInicio', 'FechaSiembra', 'FechaRecoleccion', 'finca_id', 'distrito_id', 'TipoCultivo_id', 'TipoVariedad_id', 'slug'])
            ->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'Codigo' => $data->Codigo,
                    'Distrito' => $data->Distrito->distrito,
                    'Finca' => $data->Finca->finca,
                    'NombreLote' => $data->NombreLote,
                    'TipoVariedad' => $data->TipoVariedad->TipoVariedad,
                    'TipoCultivo' => $data->TipoCultivo->TipoCultivo,
                    'FechaSiembra' => $data->FechaSiembra,
                    'FechaRecoleccion' => $data->FechaRecoleccion,
                    'Hect' => $data->Hect,
                    'slug' => $data->slug,
                    'edit_url' => route('Lote.edit', $data->slug),
                ];
            });

        // Crear un nuevo objeto Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados de las columnas
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Código');
        $sheet->setCellValue('C1', 'Distrito');
        $sheet->setCellValue('D1', 'Finca');
        $sheet->setCellValue('E1', 'Nombre del Lote');
        $sheet->setCellValue('F1', 'Tipo de Variedad');
        $sheet->setCellValue('G1', 'Tipo de Cultivo');
        $sheet->setCellValue('H1', 'Fecha de Siembra');
        $sheet->setCellValue('I1', 'Fecha de Recolección');
        $sheet->setCellValue('J1', 'Hectáreas');
        $sheet->setCellValue('K1', 'URL de Edición');

        // Escribir los datos en el archivo Excel
        $row = 2;
        foreach ($registroLotes as $registro) {
            $sheet->setCellValue('A' . $row, $registro['id']);
            $sheet->setCellValue('B' . $row, $registro['Codigo']);
            $sheet->setCellValue('C' . $row, $registro['Distrito']);
            $sheet->setCellValue('D' . $row, $registro['Finca']);
            $sheet->setCellValue('E' . $row, $registro['NombreLote']);
            $sheet->setCellValue('F' . $row, $registro['TipoVariedad']);
            $sheet->setCellValue('G' . $row, $registro['TipoCultivo']);
            $sheet->setCellValue('H' . $row, $registro['FechaSiembra']);
            $sheet->setCellValue('I' . $row, $registro['FechaRecoleccion']);
            $sheet->setCellValue('J' . $row, $registro['Hect']);
            $sheet->setCellValue('K' . $row, $registro['edit_url']);
            $row++;
        }

        // Configurar la respuesta para descargar el archivo
        $fileName = 'registro_lotes.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/' . $fileName));

        // Retornar una respuesta para descargar el archivo Excel
        return response()->download(storage_path('app/public/' . $fileName))->deleteFileAfterSend();
    }

    /**
     * Importa Excel No Probado Ojo
     */

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('file');

        try {
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Iterar sobre las filas del archivo Excel (ignorando la primera fila de encabezados)
            foreach (array_slice($rows, 1) as $row) {
                // Ejemplo: Crear un nuevo registro en la base de datos (deberías adaptar esto según tu estructura)
                RegistroLotes::create([
                    'Codigo' => $row[1],
                    'NombreLote' => $row[4],
                    'FechaSiembra' => $row[6],
                    'FechaRecoleccion' => $row[7],
                    'Hect' => $row[9],
                    // Añadir más campos según sea necesario
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Datos importados correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al importar desde Excel: ' . $e->getMessage()]);
        }


        /***
         * Vue
         const file = ref(null);

const handleFileUpload = () => {
    file.value = $refs.file.files[0];
};

const importarExcel = async () => {
    if (!file.value) {
        console.error('Debe seleccionar un archivo.');
        return;
    }

    let formData = new FormData();
    formData.append('file', file.value);

    try {
        const response = await axios.post('/import-excel', formData);
        console.log('Importación exitosa:', response.data);
        // Mostrar mensaje de éxito o realizar otras acciones según sea necesario
    } catch (error) {
        console.error('Error al importar desde Excel:', error);
    }
};
         */
    }

    public function dataconsultaSiembra()
    {
        $regLote = DB::table('fincas')
            ->select('fincas.finca', DB::raw('COALESCE(SUM(registro_lotes.Hect), 0) as hectareastotales'))
            ->leftJoin('registro_lotes', function ($join) {
                $join->on('registro_lotes.finca_id', '=', 'fincas.id');
                $join->whereYear('registro_lotes.FechaSiembra', date('Y'));
                $join->whereMonth('registro_lotes.FechaSiembra', date('m'));
            })
            ->groupBy('fincas.finca')
            ->get();

        $data = [
            'labels' => $regLote->pluck('finca')->toArray(),
            'values' => $regLote->pluck('hectareastotales')->toArray(),
        ];

        return response()->json($data);
    }


    public function dataconsultaActivoSinSiembra()
    {

        $regLote = DB::table('fincas')
            ->select('fincas.finca', DB::raw('COALESCE(SUM(registro_lotes.Hect), 0) as hectareastotales'))
            ->leftJoin('registro_lotes', function ($join) {
                $join->on('registro_lotes.finca_id', '=', 'fincas.id');
                $join->whereNull('registro_lotes.FechaSiembra');
                $join->whereNull('registro_lotes.FechaRecoleccion');
            })
            ->groupBy('fincas.finca')
            ->get();

        $data = [
            'labels' => $regLote->pluck('finca')->toArray(),
            'values' => $regLote->pluck('hectareastotales')->toArray(),
        ];

        return response()->json($data);
    }

    public function dataconsultaActivosConSiembraSinRecoleccion()
    {

        $regLote = DB::table('fincas')
            ->select('fincas.finca', DB::raw('COALESCE(SUM(registro_lotes.Hect), 0) as hectareastotales'))
            ->leftJoin('registro_lotes', function ($join) {
                $join->on('registro_lotes.finca_id', '=', 'fincas.id');
                $join->whereNotNull('registro_lotes.FechaSiembra');
                $join->whereNull('registro_lotes.FechaRecoleccion');
            })
            ->groupBy('fincas.finca')
            ->get();

        $data = [
            'labels' => $regLote->pluck('finca')->toArray(),
            'values' => $regLote->pluck('hectareastotales')->toArray(),
        ];

        return response()->json($data);
    }


    public function DataChartEstadosLotes()
    {
        // Tamero
        $Tamero = DB::table('fincas')
            ->select('fincas.finca', DB::raw('COALESCE(SUM(registro_lotes.Hect), 0) as hectareastotales'))
            ->leftJoin('registro_lotes', function ($join) {
                $join->on('registro_lotes.finca_id', '=', 'fincas.id');
                $join->whereNotNull('registro_lotes.FechaSiembra');
                $join->whereNotNull('registro_lotes.FechaGerminacion');
                $join->whereYear('registro_lotes.FechaRecoleccion', date('Y'));
                $join->whereMonth('registro_lotes.FechaRecoleccion', date('m'));
            })
            ->groupBy('fincas.finca')
            ->get()
            ->map(function ($data) {
                return [
                    'finca' => $data->finca,
                    'hectareastotales' => $data->hectareastotales,
                ];
            });

        // Crecimiento Cuidado
        $CrecimientoCuidado = DB::table('fincas')
            ->select('fincas.finca', DB::raw('COALESCE(SUM(registro_lotes.Hect), 0) as hectareastotales'))
            ->leftJoin('registro_lotes', function ($join) {
                $join->on('registro_lotes.finca_id', '=', 'fincas.id');
                $join->whereNotNull('registro_lotes.FechaSiembra');
                $join->whereNotNull('registro_lotes.FechaGerminacion');
                $join->whereNull('registro_lotes.FechaRecoleccion');
            })
            ->groupBy('fincas.finca')
            ->get()
            ->map(function ($data) {
                return [
                    'finca' => $data->finca,
                    'hectareastotales' => $data->hectareastotales,
                ];
            });

        // Siembra
        $Siembra = DB::table('fincas')
            ->select('fincas.finca', DB::raw('COALESCE(SUM(registro_lotes.Hect), 0) as hectareastotales'))
            ->leftJoin('registro_lotes', function ($join) {
                $join->on('registro_lotes.finca_id', '=', 'fincas.id');
                $join->whereNotNull('registro_lotes.FechaSiembra');
                $join->whereNull('registro_lotes.FechaGerminacion');
                $join->whereNull('registro_lotes.FechaRecoleccion');
            })
            ->groupBy('fincas.finca')
            ->get()
            ->map(function ($data) {
                return [
                    'finca' => $data->finca,
                    'hectareastotales' => $data->hectareastotales,
                ];
            });

        // PreparacionTerreno
        $PreparacioTerreno = DB::table('fincas')
            ->select('fincas.finca', DB::raw('COALESCE(SUM(registro_lotes.Hect), 0) as hectareastotales'))
            ->leftJoin('registro_lotes', function ($join) {
                $join->on('registro_lotes.finca_id', '=', 'fincas.id');
                $join->whereNull('registro_lotes.FechaSiembra');
                $join->whereNull('registro_lotes.FechaGerminacion');
                $join->whereNull('registro_lotes.FechaRecoleccion');
                $join->where(function ($query) {
                    $query->whereYear('registro_lotes.FechaInicio', '<>', date('Y'))
                        ->orWhereMonth('registro_lotes.FechaInicio', '<>', date('m'));
                });
            })
            ->groupBy('fincas.finca')
            ->get()
            ->map(function ($data) {
                return [
                    'finca' => $data->finca,
                    'hectareastotales' => $data->hectareastotales,
                ];
            });



        $data = [
            'PreparacioTerreno' => [
                'labels' => $PreparacioTerreno->pluck('finca')->toArray(),
                'values' => $PreparacioTerreno->pluck('hectareastotales')->toArray(),
            ],
            'Siembra' => [
                'labels' => $Siembra->pluck('finca')->toArray(),
                'values' => $Siembra->pluck('hectareastotales')->toArray(),
            ],
            'CrecimientoCuidado' => [
                'labels' => $CrecimientoCuidado->pluck('finca')->toArray(),
                'values' => $CrecimientoCuidado->pluck('hectareastotales')->toArray(),
            ],
            'Tamero' => [
                'labels' => $Tamero->pluck('finca')->toArray(),
                'values' => $Tamero->pluck('hectareastotales')->toArray(),
            ]
        ];

        return response()->json($data);
    }

    public function DataChartStatusLoteCrecimientoCuidado()
    {
        // Plantula
        $Plantula = DB::table('fincas')
            ->select('fincas.finca', DB::raw('COALESCE(SUM(registro_lotes.Hect), 0) as hectareastotales'))
            ->leftJoin('registro_lotes', function ($join) {
                $join->on('registro_lotes.finca_id', '=', 'fincas.id');
                $join->whereNotNull('registro_lotes.FechaSiembra');
                $join->whereNotNull('registro_lotes.FechaGerminacion');
                $join->whereNull('registro_lotes.FechaRecoleccion');
                $join->whereRaw('DATEDIFF(CURDATE(), registro_lotes.FechaGerminacion) BETWEEN 1 AND 9');
            })
            ->groupBy('fincas.finca')
            ->get()
            ->map(function ($data) {
                return [
                    'finca' => $data->finca,
                    'hectareastotales' => $data->hectareastotales,
                    'status' => 'Plantula',
                ];
            });

        // Maximo Macollamiento
        $MaximoMacollamiento = DB::table('fincas')
            ->select('fincas.finca', DB::raw('COALESCE(SUM(registro_lotes.Hect), 0) as hectareastotales'))
            ->leftJoin('registro_lotes', function ($join) {
                $join->on('registro_lotes.finca_id', '=', 'fincas.id');
                $join->whereNotNull('registro_lotes.FechaSiembra');
                $join->whereNotNull('registro_lotes.FechaGerminacion');
                $join->whereNull('registro_lotes.FechaRecoleccion');
                $join->whereRaw('DATEDIFF(CURDATE(), registro_lotes.FechaGerminacion) BETWEEN 10 AND 34');
            })
            ->groupBy('fincas.finca')
            ->get()
            ->map(function ($data) {
                return [
                    'finca' => $data->finca,
                    'hectareastotales' => $data->hectareastotales,
                    'status' => 'Maximo Macollamiento',
                ];
            });

        // Primordio
        $Primordio = DB::table('fincas')
            ->select('fincas.finca', DB::raw('COALESCE(SUM(registro_lotes.Hect), 0) as hectareastotales'))
            ->leftJoin('registro_lotes', function ($join) {
                $join->on('registro_lotes.finca_id', '=', 'fincas.id');
                $join->whereNotNull('registro_lotes.FechaSiembra');
                $join->whereNotNull('registro_lotes.FechaGerminacion');
                $join->whereNull('registro_lotes.FechaRecoleccion');
                $join->whereRaw('DATEDIFF(CURDATE(), registro_lotes.FechaGerminacion) BETWEEN 35 AND 49');
            })
            ->groupBy('fincas.finca')
            ->get()
            ->map(function ($data) {
                return [
                    'finca' => $data->finca,
                    'hectareastotales' => $data->hectareastotales,
                    'status' => 'Primordio',
                ];
            });

        // Antesis
        $Antesis = DB::table('fincas')
            ->select('fincas.finca', DB::raw('COALESCE(SUM(registro_lotes.Hect), 0) as hectareastotales'))
            ->leftJoin('registro_lotes', function ($join) {
                $join->on('registro_lotes.finca_id', '=', 'fincas.id');
                $join->whereNotNull('registro_lotes.FechaSiembra');
                $join->whereNotNull('registro_lotes.FechaGerminacion');
                $join->whereNull('registro_lotes.FechaRecoleccion');
                $join->whereRaw('DATEDIFF(CURDATE(), registro_lotes.FechaGerminacion) BETWEEN 50 AND 59');
            })
            ->groupBy('fincas.finca')
            ->get()
            ->map(function ($data) {
                return [
                    'finca' => $data->finca,
                    'hectareastotales' => $data->hectareastotales,
                    'status' => 'Antesis',
                ];
            });

        // Floracion
        $Floracion = DB::table('fincas')
            ->select('fincas.finca', DB::raw('COALESCE(SUM(registro_lotes.Hect), 0) as hectareastotales'))
            ->leftJoin('registro_lotes', function ($join) {
                $join->on('registro_lotes.finca_id', '=', 'fincas.id');
                $join->whereNotNull('registro_lotes.FechaSiembra');
                $join->whereNotNull('registro_lotes.FechaGerminacion');
                $join->whereNull('registro_lotes.FechaRecoleccion');
                $join->whereRaw('DATEDIFF(CURDATE(), registro_lotes.FechaGerminacion) BETWEEN 60 AND 89');
            })
            ->groupBy('fincas.finca')
            ->get()
            ->map(function ($data) {
                return [
                    'finca' => $data->finca,
                    'hectareastotales' => $data->hectareastotales,
                    'status' => 'Floracion',
                ];
            });


        // Llenado
        $Llenado = DB::table('fincas')
            ->select('fincas.finca', DB::raw('COALESCE(SUM(registro_lotes.Hect), 0) as hectareastotales'))
            ->leftJoin('registro_lotes', function ($join) {
                $join->on('registro_lotes.finca_id', '=', 'fincas.id');
                $join->whereNotNull('registro_lotes.FechaSiembra');
                $join->whereNotNull('registro_lotes.FechaGerminacion');
                $join->whereNull('registro_lotes.FechaRecoleccion');
                $join->whereRaw('DATEDIFF(CURDATE(), registro_lotes.FechaGerminacion) BETWEEN 90 AND 99');
            })
            ->groupBy('fincas.finca')
            ->get()
            ->map(function ($data) {
                return [
                    'finca' => $data->finca,
                    'hectareastotales' => $data->hectareastotales,
                    'status' => 'Llenado',
                ];
            });

        // Maduracion
        $Maduracion = DB::table('fincas')
            ->select('fincas.finca', DB::raw('COALESCE(SUM(registro_lotes.Hect), 0) as hectareastotales'))
            ->leftJoin('registro_lotes', function ($join) {
                $join->on('registro_lotes.finca_id', '=', 'fincas.id');
                $join->whereNotNull('registro_lotes.FechaSiembra');
                $join->whereNotNull('registro_lotes.FechaGerminacion');
                $join->whereNull('registro_lotes.FechaRecoleccion');
                $join->whereRaw('DATEDIFF(CURDATE(), registro_lotes.FechaGerminacion) BETWEEN 100 AND 109');
            })
            ->groupBy('fincas.finca')
            ->get()
            ->map(function ($data) {
                return [
                    'finca' => $data->finca,
                    'hectareastotales' => $data->hectareastotales,
                    'status' => 'Maduracion',
                ];
            });

        // Cosecha
        $Cosecha = DB::table('fincas')
            ->select('fincas.finca', DB::raw('COALESCE(SUM(registro_lotes.Hect), 0) as hectareastotales'))
            ->leftJoin('registro_lotes', function ($join) {
                $join->on('registro_lotes.finca_id', '=', 'fincas.id');
                $join->whereNotNull('registro_lotes.FechaSiembra');
                $join->whereNotNull('registro_lotes.FechaGerminacion');
                $join->whereNull('registro_lotes.FechaRecoleccion');
                $join->whereRaw('DATEDIFF(CURDATE(), registro_lotes.FechaGerminacion) > 110');
            })
            ->groupBy('fincas.finca')
            ->get()
            ->map(function ($data) {
                return [
                    'finca' => $data->finca,
                    'hectareastotales' => $data->hectareastotales,
                    'status' => 'Cosecha',
                ];
            });

        $data = [
            'Plantula' => [
                'labels' => $Plantula->pluck('finca')->toArray(),
                'values' => $Plantula->pluck('hectareastotales')->toArray(),
            ],
            'MaximoMacollamiento' => [
                'labels' => $MaximoMacollamiento->pluck('finca')->toArray(),
                'values' => $MaximoMacollamiento->pluck('hectareastotales')->toArray(),
            ],
            'Primordio' => [
                'labels' => $Primordio->pluck('finca')->toArray(),
                'values' => $Primordio->pluck('hectareastotales')->toArray(),
            ],
            'Antesis' => [
                'labels' => $Antesis->pluck('finca')->toArray(),
                'values' => $Antesis->pluck('hectareastotales')->toArray(),
            ],
            'Floracion' => [
                'labels' => $Floracion->pluck('finca')->toArray(),
                'values' => $Floracion->pluck('hectareastotales')->toArray(),
            ],
            'Llenado' => [
                'labels' => $Llenado->pluck('finca')->toArray(),
                'values' => $Llenado->pluck('hectareastotales')->toArray(),
            ],
            'Maduracion' => [
                'labels' => $Maduracion->pluck('finca')->toArray(),
                'values' => $Maduracion->pluck('hectareastotales')->toArray(),
            ],
            'Cosecha' => [
                'labels' => $Cosecha->pluck('finca')->toArray(),
                'values' => $Cosecha->pluck('hectareastotales')->toArray(),
            ],

        ];

        return response()->json($data);
    }
}
