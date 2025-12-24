<?php

namespace App\Http\Controllers\Config\TipoVariedad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Config\TipoVariedad\StoreTipoVariedadRequest;
use App\Http\Requests\Config\TipoVariedad\UpdateTipoVariedadRequest;
use App\Models\TipoVariedad;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TipoVariedadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Consulta
        $data = TipoVariedad::select(['id', 'TipoVariedad', 'slug'])->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'TipoVariedad' => $data->TipoVariedad,
                'edit_url' => route('TipoVariedad.edit', $data->slug),
            ];
        });
        //Renderizar Vista
        return Inertia::render('Config/TipoVariedad/Explorar', [
            'data' => $data,
            'create_url' => route('TipoCultivo.Crear'),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Config/TipoVariedad/Crear', [
            // Traer los datos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoVariedadRequest $request)
    {
        //
        //
        $form = new TipoVariedad;
        $form->TipoVariedad = $request->TipoVariedad;
        $form->save();

        return redirect(route('TipoVariedad.Explorar'));
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoVariedad $tipoVariedad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        //
        // Consulta
        $data = TipoVariedad::where('slug', $slug)->firstOrFail();
        //Renderizar Vista
        return Inertia::render('Config/TipoVariedad/Edit', [
            'datos' => [
                'id' => $data->id,
                'TipoVariedad' => $data->TipoVariedad,
                'slug' => $data->slug,
                'update_url' => route('TipoVariedad.update', $data->slug),
                'delete_url' => route('TipoVariedad.delete', $data->slug),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoVariedadRequest $request, $slug)
    {
        //
        // Actualizar
        $data = TipoVariedad::where('slug', $slug)->firstOrFail();
        $data->TipoVariedad = $request->TipoVariedad;
        $data->slug = Str::of($request->finca)->slug();
        $data->save();

        return redirect(route('TipoVariedad.edit', $data->slug));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        //
        $data =  TipoVariedad::where('slug', $slug)->firstOrFail();
        $data->delete();
        return redirect(route('TipoVariedad.Explorar'));
    }
}
