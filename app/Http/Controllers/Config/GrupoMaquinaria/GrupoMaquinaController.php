<?php

namespace App\Http\Controllers\Config\GrupoMaquinaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\Config\GrupoMaquina\StoreGrupoMaquinaRequest;
use App\Http\Requests\Config\GrupoMaquina\UpdateGrupoMaquinaRequest;
use App\Models\GrupoMaquina;
use Inertia\Inertia;
use Illuminate\Support\Str;

class GrupoMaquinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Consulta
        $data = GrupoMaquina::select(['id', 'GrupoMaquina', 'slug'])->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'GrupoMaquina' => $data->GrupoMaquina,
                'edit_url' => route('GrupoMaquina.edit', $data->slug),
            ];
        });
        //Renderizar Vista
        return Inertia::render('Config/GrupoMaquina/Explorar', [
            'data' => $data,
            'create_url' => route('GrupoMaquina.Crear'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Config/GrupoMaquina/Crear', [
            // Traer los datos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGrupoMaquinaRequest $request)
    {
        //
        $form = new GrupoMaquina;
        $form->GrupoMaquina = $request->GrupoMaquina;
        $form->save();

        return redirect(route('GrupoMaquina.Explorar'));
    }

    /**
     * Display the specified resource.
     */
    public function show(GrupoMaquina $grupoMaquina)
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
         $data = GrupoMaquina::where('slug', $slug)->firstOrFail();
         //Renderizar Vista
         return Inertia::render('Config/GrupoMaquina/Edit', [
             'datos' => [
                 'id' => $data->id,
                 'GrupoMaquina' => $data->GrupoMaquina,
                 'slug' => $data->slug,
                 'update_url' => route('GrupoMaquina.update', $data->slug),
                 'delete_url' => route('GrupoMaquina.delete', $data->slug),
             ],
         ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGrupoMaquinaRequest $request, $slug)
    {
        //
        // Actualizar
        $data = GrupoMaquina::where('slug', $slug)->firstOrFail();
        $data->GrupoMaquina = $request->GrupoMaquina;
        $data->slug = Str::of($request->GrupoMaquina)->slug();
        $data->save();

        return redirect(route('GrupoMaquina.edit', $data->slug));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        //
        $data =  GrupoMaquina::where('slug', $slug)->firstOrFail();
        $data->delete();
        return redirect(route('GrupoMaquina.Explorar'));
    }
}
