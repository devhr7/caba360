<?php

namespace App\Http\Controllers\Config\Finca;

use App\Http\Controllers\Controller;
use App\Http\Requests\Config\Finca\StoreFincaRequest;
use App\Http\Requests\Config\Finca\UpdateFincaRequest;
use App\Models\Distrito;
use App\Models\Finca;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class FincaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Consulta
        $finca = Finca::with('Distrito:id,distrito')->select(['id', 'distrito_id', 'finca', 'slug'])->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'distrito_id' => $data->Distrito->distrito,
                'finca' => $data->finca,
                'edit_url' => route('Finca.edit', $data->slug),
            ];
        });

        //Renderizar Vista
        
        return Inertia::render('Config/Finca/Explorar', [
            'finca' => $finca,

            'create_url' => route('Finca.Crear'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Config/Finca/Crear',[
             // Traer los datos del distrito para el auto completar
             'get_distrito' => Distrito::all()->map(function($reg){
                return [
                    "id" => $reg->id,
                    "name" => $reg->distrito
                ];
            }),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFincaRequest $request)
    {
        //
        $Finca = new Finca;
        $Finca->distrito_id = $request->distrito['id'];
        $Finca->finca = $request->finca;
        $Finca->save();

        return redirect(route('Finca.Explorar'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Finca $finca)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        // Consulta
        $finca = Finca::where('slug', $slug)->firstOrFail();
        //Renderizar Vista
        return Inertia::render('Config/Finca/Edit', [
            'datos' => [
                'id' => $finca->id,
                'distrito' => [
                   'id' => $finca->distrito_id, // Asegúrate de usar el campo correcto
                   "name" => Distrito::where("id", $finca->distrito_id)->first()->distrito //
                ],
                'finca' => $finca->finca,
                'slug' => $finca->slug,
                'update_url' => route('Finca.update', $finca->slug),
                'delete_url' => route('Finca.delete', $finca->slug),
            ],
            // Traer los datos del distrito para el auto completar
            'get_distrito' => Distrito::all()->map(function($reg){
                return [
                    "id" => $reg->id,
                    "name" => $reg->distrito
                ];
            }),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFincaRequest $request, $slug)
    {
        // Actualizar
        $Finca = Finca::where('slug', $slug)->firstOrFail();
        $Finca->Finca = $request->finca;
        $Finca->distrito_id = $request->distrito['id'];
        $Finca->slug = Str::of($request->finca)->slug();
        $Finca->save();

        return redirect(route('Finca.edit', $Finca->slug));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        //
        $Finca =  Finca::where('slug', $slug)->firstOrFail();
        $Finca->delete();
        return redirect(route('Finca.Explorar'));
    }

    /**
     * Opcions Select
     */

    /**
     * Opciones para Select Finca
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function getOptionsFinca(Request $request)
    {
        $data = Finca::select(['id', 'finca', 'slug'])->where('distrito_id',  $request['id'])->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'label' => $data->finca,
            ];
        });
        return response()->json($data);
    }


    public function dataconsultaHectareasFinca()
    {
        $Finca = Finca::select('id','finca', 'metahect')
            ->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'finca' => $data->finca,
                    'metahect' => $data->metahect,
                    'labels' => $data->finca,
                    'values' => $data->metahect
                ];
            });

        $data = [
                'labels' => $Finca->pluck('labels')->toArray(),
                'values' => $Finca->pluck('values')->toArray(),
        ];

        return response()->json($data);
    }
}
