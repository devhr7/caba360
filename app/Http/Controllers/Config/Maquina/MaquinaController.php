<?php

namespace App\Http\Controllers\Config\Maquina;

use App\Http\Controllers\Controller;
use App\Http\Requests\Config\Maquinaria\StoreMaquinaRequest;
use App\Http\Requests\Config\Maquinaria\UpdateMaquinaRequest;
use App\Models\Maquina;
use Illuminate\Support\Str;
use Inertia\Inertia;


class MaquinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         // Consulta
         $data = Maquina::select(['id', 'GrupoMaquina', 'slug'])->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'GrupoMaquina' => $data->GrupoMaquina,
                'edit_url' => route('GrupoMaquina.edit', $data->slug),
            ];
        });
        //Renderizar Vista
        return Inertia::render('Config/Maquina/Explorar', [
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaquinaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Maquina $maquina)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maquina $maquina)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaquinaRequest $request, Maquina $maquina)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maquina $maquina)
    {
        //
    }
}
