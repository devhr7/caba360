<?php

namespace App\Http\Controllers\Config\Lote;

use App\Http\Controllers\Controller;
use App\Http\Requests\Config\Lote\StoreLoteRequest;
use App\Http\Requests\Config\Lote\UpdateLoteRequest;
use App\Models\Distrito;
use App\Models\Finca;
use App\Models\Lote;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;


class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Consulta
        $Lote = Lote::with(['Distrito:id,distrito','Finca:id,finca'])->select(['id', 'lote', 'Hect', 'finca_id','distrito_id','slug'])->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'distrito_id' => $data->Distrito->distrito,
                'finca_id' => $data->Finca->finca,
                'lote' => $data->lote,
                'Hect' => $data->Hect,
                'edit_url' => route('Lote.edit', $data->slug),
            ];
        });

        //Renderizar Vista
        return Inertia::render('Config/Lote/Explorar', [
            'lote' => $Lote,
            'create_url' => route('Lote.Crear'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Config/Lote/Crear',[
            // Traer los datos del distrito para el auto completar
            'get_distrito' => Distrito::all()->map(function($reg){
               return [
                   "id" => $reg->id,
                   "name" => $reg->distrito
               ];
           }),
           'get_finca' => Finca::all()->map(function($reg){
               return [
                   "id" => $reg->id,
                   "name" => $reg->finca
               ];
           }),
       ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoteRequest $request)
    {
        //
        $Lote = new Lote;
        $Lote->finca_id = $request->finca['id'];
        $Lote->distrito_id = $request->distrito['id'];
        $Lote->lote = Str::trim(Str::squish(Str::upper($request->lote)));
        $Lote->hect = $request->hect;
        $Lote->save();

        return redirect(route('Lote.Explorar'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Lote $lote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        // Consulta
        $Lote = Lote::where('slug', $slug)->firstOrFail();
        //Renderizar Vista
        return Inertia::render('Config/Lote/Edit', [
            'datos' => [
                'id' => $Lote->id,
                'distrito' => [
                   'id' => $Lote->distrito_id, // Asegúrate de usar el campo correcto
                   "name" => Distrito::where("id", $Lote->distrito_id)->first()->distrito //
                ],
                'finca'=> [
                    'id' => $Lote->finca_id, // Asegúrate de usar el campo correcto
                    "name" => Finca::where("id", $Lote->finca_id)->first()->finca //
                 ],
                 'lote' => $Lote->lote,
                 'hect' => $Lote->hect,
                'slug' => $Lote->slug,
                'update_url' => route('Lote.update', $Lote->slug),
                'delete_url' => route('Lote.delete', $Lote->slug),
            ],
            // Traer los datos del distrito para el auto completar
            'get_distrito' => Distrito::all()->map(function($reg){
                return [
                    "id" => $reg->id,
                    "name" => $reg->distrito
                ];
            }),
            'get_finca' => Finca::all()->map(function($reg){
               return [
                   "id" => $reg->id,
                   "name" => $reg->finca
               ];
           }),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoteRequest $request, $slug)
    {
        //
        // Actualizar
        $Lote = Lote::where('slug', $slug)->firstOrFail();
        $Lote->lote = $request->lote;
        $Lote->hect = $request->hect;
        $Lote->distrito_id = $request->distrito['id'];
        $Lote->finca_id = $request->finca['id'];
        $Lote->slug = Str::of($request->lote)->slug();
        $Lote->save();

        return redirect(route('Lote.edit', $Lote->slug));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        $Lote =  Lote::where('slug', $slug)->firstOrFail();
        $Lote->delete();
        return redirect(route('Lote.Explorar'));
    }

    /**
     * Opcions Select
     */

     public function getOptionsLote(Request $request)
    {
        $data = Lote::select(['id', 'lote','hect', 'slug'])->where('finca_id',  $request['id'])->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'label' => $data->lote,
                'hect' => $data->hect,
            ];
        });
        return response()->json($data);
    }

    public function getDataLote(Request $request)
    {
        $data = Lote::select(['id', 'lote','hect', 'slug'])->where('id',  $request['id'])->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'lote' => $data->lote,
                'hect' => $data->hect,
            ];
        })->firstOrFail();
        return response()->json($data);
    }
}
