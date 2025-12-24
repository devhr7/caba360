<?php

namespace App\Http\Controllers\Modulos\Records\RecordVisita;

use App\Http\Controllers\Config\MateriaPrima\MateriaPrimaController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Modulos\Agronomo\RecordVisita\StoreRecordVisitaRequest;
use App\Http\Requests\Modulos\Agronomo\RecordVisita\UpdateRecordVisitaRequest;
use App\Http\Resources\Modulos\Record\RecordCollection;
use App\Models\Empleados;
use App\Models\Finca;
use App\Models\Grupo_MateriaPrima;
use App\Models\Labor;
use App\Models\Lote;
use App\Models\MateriaPrima;
use App\Models\ProductoRecordVisitas;
use App\Models\RecordVisita;
use App\Models\RegistroLotes;
use Carbon\Carbon;
use Illuminate\Http\Request;




use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;





class RecordVisitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // Verificar permisos manualmente en el controlador
        if (!Auth::user()->can('mod.recordvisita.show')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        // Consulta
        $RecordVisita = RecordVisita::with(['Distrito:id,distrito', 'Finca:id,finca', 'Lote:id,lote', 'RegistroLotes:id,Codigo'])->select(['id', 'slug', 'fecha', 'Codigo', 'distrito_id', 'finca_id', 'lote_id', 'RegLote_id'])->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'codigo' => $data->Codigo,
                'fecha' => $data->fecha,
                'distrito' => $data->Distrito->distrito,
                'finca' => $data->Finca->finca . " | " . $data->Lote->lote,
                'codigo_lote' => $data->RegistroLotes->Codigo,
                'edit_url' => route('RecordVisita.edit', $data->slug),
            ];
        });

        //Renderizar Vista
        return inertia::render('Modulos/Records/RecordVisita/Explorar', [
            'RecordVisita' => $RecordVisita,
            'create_url' => route('RecordVisita.crear'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        // Verificar permisos manualmente en el controlador
        if (!Auth::user()->can('mod.recordvisita.create')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        return Inertia::render('Modulos/Records/RecordVisita/Crear', [
            // Traer Los datos de los Options selects
            'get_finca' => Finca::optionsFinca(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRecordVisitaRequest $request)
    {
        $RecordVisita = RecordVisita::create([
            'Codigo' => $request->CodigoRecord,
            'fecha' => empty($request->FechaRecord) ? null :  Carbon::parse($request->FechaRecord)->format('Y-m-d'),
            'distrito_id' => Finca::find($request->finca['id'])->distrito_id,
            'finca_id' => $request->finca['id'],
            'lote_id' => $request->lote['id'],
            'RegLote_id' => $request->RegLote_id,
            //'AgricultorEncargado_id' => $request->GrupoMateriaPrima,
            //'IngenieroAgronomo_id' => $request->GrupoMateriaPrima,
            'dias_emergencia' => $request->DiaGerminacion,
            'hect_aplicacion' => $request->Hectareas,
            'diagnostico' => $request->Diagnostico,
            'observaciones' => $request->Observaciones,
        ]);
        return redirect()->route('RecordVisita.edit', $RecordVisita->slug)->with('success', 'Record Visita Agronomo creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(RecordVisita $recordVisita)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        // Verificar permisos manualmente en el controlador
        if (! Gate::allows('mod.recordvisita.show')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        // Consulta
        $RecordVisita = RecordVisita::where('slug', $slug)->firstOrFail();
        //Renderizar Vista
        return Inertia::render(
            'Modulos/Records/RecordVisita/Edit',
            [
                // Traer Los Datos de la Consulta
                'datos' => [
                    'id' => $RecordVisita->id, // Asegúrate de usar el campo correcto
                    'Codigo' => $RecordVisita->Codigo, // Asegúrate de usar el campo correcto
                    'fecha' => $RecordVisita->fecha, // Asegúrate de usar el campo correcto
                    'Finca' => Finca::optionsFinca(null,$RecordVisita->finca_id),
                    'lote' => Lote::optionsLote($RecordVisita->finca_id, $RecordVisita->lote_id),
                    'RegLote' => RegistroLotes::find($RecordVisita->RegLote_id),
                    'AgricultorEncargado' => $RecordVisita->AgricultorEncargado_id, // Asegúrate de usar el campo correcto
                    'IngenieroAgronomo' => $RecordVisita->IngenieroAgronomo_id, // Asegúrate de usar el campo correcto
                    'hect_aplicacion' => $RecordVisita->hect_aplicacion, // Asegúrate de usar el campo correcto
                    'dias_emergencia' =>$RecordVisita->dias_emergencia,
                    'diagnostico' => $RecordVisita->diagnostico, // Asegúrate de usar el campo correcto
                    'observaciones' => $RecordVisita->observaciones, // Asegúrate de usar el campo correcto
                    'slug' => $RecordVisita->slug,
                    'UrlAddProduct' => route('RecordVisita.Producto.store', $RecordVisita->slug),
                ],

                'DatosProductoVisita' => ProductoRecordVisitas::with(['grupoMPrima:id,GrupoMateriaPrima', 'Producto:id,MateriaPrima'])
                    ->select('id', 'slug', 'RecordVisita_id', 'GrupoMateriaPrima_id', 'producto_id', 'Dosis_por_Ha', 'cantidad_Total', 'fecha_estimada_aplicacion')
                    ->where('RecordVisita_id', $RecordVisita->id)
                    ->get()->map(function ($data) {
                        return [
                            'id' => $data->id,
                            'slug' => $data->slug,
                            'GrupoMateriaPrima' => $data->grupoMPrima->GrupoMateriaPrima,
                            'Producto' => $data->Producto->MateriaPrima,
                            'Dosis_por_Ha' => $data->Dosis_por_Ha,
                            'cantidad_Total' => $data->cantidad_Total,
                            'fecha_estimada_aplicacion' => $data->fecha_estimada_aplicacion,
                        ];
                    }),
                // Options selects
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
                'get_GrupoMateriaPrima' => Grupo_MateriaPrima::all()->map(function ($reg) {
                    return [
                        "id" => $reg->id,
                        "label" => $reg->GrupoMateriaPrima
                    ];
                }),

                'get_MateriaPrima' => MateriaPrima::optionsMateriaPrima(request()->get('GrupoMPrima_id')),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRecordVisitaRequest $request, $RecordVisita)
    {
        // Buscar el Grupo Materia Prima por Slug
        $RecordVisita = RecordVisita::where('slug', $RecordVisita)->firstOrFail();

        $RecordVisita->update([
            'Codigo' => $request->CodigoRecord,
            'fecha' => empty($request->FechaRecord) ? null :  Carbon::parse($request->FechaRecord)->format('Y-m-d'),
            'distrito_id' => Finca::find($request->finca['id'])->distrito_id,
            'finca_id' => $request->finca['id'],
            'lote_id' => $request->lote['id'],
            'RegLote_id' => $request->RegLote_id,
            'hect_aplicacion' => $request->Hectareas,
            'dias_emergencia' => $request->DiaGerminacion,
            'diagnostico' => $request->Diagnostico,
            'observaciones' => $request->Observaciones,
            'AgricultorEncargado_id' => $request->AgricultorEncargado['id'] ?? null,
            'IngenieroAgronomo_id' => $request->IngenieroAgronomo['id'] ?? null,
            //'slug' => Str::of($request->GrupoMateriaPrima)->slug(),
        ]);


        // Actualizar la cantidad total de cada producto asociado a la record de visita
        // Se utiliza el metodo each para iterar sobre la coleccion de productos asociados
        // y se actualiza la cantidad total de cada producto multiplicando la dosis por hectarea
        // por el numero de hectareas de la record de visita
        $ProductoRecordVisitas = ProductoRecordVisitas::select('id', 'slug', 'RecordVisita_id', 'GrupoMateriaPrima_id', 'producto_id', 'Dosis_por_Ha', 'cantidad_Total', 'fecha_estimada_aplicacion')
            ->where('RecordVisita_id', $RecordVisita->id)
            ->get();
        $ProductoRecordVisitas->each(function ($prv) use ($RecordVisita) {
            $prv->update([
                'cantidad_Total' => $prv->Dosis_por_Ha * $RecordVisita->hect_aplicacion,
            ]);
        });

        // Verificar si el Grupo Materia Prima se actualizo correctamente
        if ($RecordVisita instanceof RecordVisita) {
            // Redirigir al usuario a la pantalla de editar con un mensaje de confirmacion
            return redirect(route('RecordVisita.edit', $RecordVisita->slug))
                ->with('success', 'Record Visita Actualizado correctamente');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        //
        $RecordVisita =  RecordVisita::where('slug', $slug)->firstOrFail();
        // Eliminar todos los registros ProductoRecordVisitas asociados a la RecordVisita
        ProductoRecordVisitas::where('RecordVisita_id', $RecordVisita->id)->delete();
        $RecordVisita->delete();
        return redirect(route('RecordVisita.Explorar'));
    }

    public function getrecordinfo(Request $request){
        $CodigoRecord = $request->CodigoRecord;
        $data = RecordVisita::select('id','Codigo','finca_id','lote_id','RegLote_id')
        ->where('Codigo', $CodigoRecord)
        ->get()->map(function ($data) {
            return [
                'id'=> $data->id,
                'Codigo' => $data->Codigo,
                'Finca' =>Finca::optionsFinca(null,$data->finca_id),
                'lote' => Lote::optionsLote($data->finca_id, $data->lote_id),
                'RegLote' => RegistroLotes::find($data->RegLote_id),
            ];
        })->firstOrFail();


        // Devuelve la información en formato JSON

        return response()->json($data);

    }


    public function getrecords()
    {
        $records = ProductoRecordVisitas::with('recordVisita','Producto','grupoMPrima')->get();
        return new RecordCollection($records);

    }

}
