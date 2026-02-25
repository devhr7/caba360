<?php

namespace App\Http\Controllers\Config\TipoCultivo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Config\TipoCultivo\StoreTipoCultivoRequest;
use App\Http\Requests\Config\TipoCultivo\UpdateTipoCultivoRequest;
use App\Models\TipoCultivo;
use Illuminate\Support\Str;

use Inertia\Inertia;

class TipoCultivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Consulta
        $data = TipoCultivo::select(['id', 'TipoCultivo', 'slug'])->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'TipoCultivo' => $data->TipoCultivo,
                'edit_url' => route('TipoCultivo.edit', $data->slug),
            ];
        });
        //Renderizar Vista
        return Inertia::render('Config/TipoCultivo/Explorar', [
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
        //
        return Inertia::render('Config/TipoCultivo/Crear', [
            // Traer los datos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoCultivoRequest $request)
    {
        //
        $form = new TipoCultivo;
        $form->TipoCultivo = $request->TipoCultivo;
        $form->save();

        return redirect(route('TipoCultivo.Explorar'));
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoCultivo $tipoCultivo)
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
        $data = TipoCultivo::where('slug', $slug)->firstOrFail();
        //Renderizar Vista
        return Inertia::render('Config/TipoCultivo/Edit', [
            'datos' => [
                'id' => $data->id,
                'TipoCultivo' => $data->TipoCultivo,
                'slug' => $data->slug,
                'update_url' => route('TipoCultivo.update', $data->slug),
                'delete_url' => route('TipoCultivo.delete', $data->slug),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoCultivoRequest $request, $slug)
    {
        //
        // Actualizar
        $data = TipoCultivo::where('slug', $slug)->firstOrFail();
        $data->TipoCultivo = $request->TipoCultivo;
        $data->slug = Str::of($request->finca)->slug();
        $data->save();

        return redirect(route('TipoCultivo.edit', $data->slug));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {

        //
        $data =  TipoCultivo::where('slug', $slug)->firstOrFail();
        $data->delete();
        return redirect(route('TipoCultivo.Explorar'));
    }
}
