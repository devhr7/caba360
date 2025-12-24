<?php

namespace App\Http\Controllers\Modulos\Costos\Parametros;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modulos\Costos\Gastos\StoresubtipogastoRequest;
use App\Models\subtipogasto;

use App\Http\Requests\UpdatesubtipogastoRequest;
use App\Models\gasto;
use App\Models\TipoGasto;
use Inertia\Inertia;

use function PHPUnit\Framework\isNull;

class SubtipogastoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $subtipogasto = subtipogasto::with('tipogasto')->get()->map(function ($subtipogasto) {
            return [
                'id' => $subtipogasto->id,
                'codigo' => $subtipogasto->codigo,
                'nombre' => $subtipogasto->nombre,
                'tipogasto' => $subtipogasto->tipogasto->nombre,
                'gasto' => $subtipogasto->tipogasto->gasto->nombre,
                'optionsGasto' => gasto::optionsGasto($subtipogasto->tipogasto->gasto->id),
                'optionsTipoGasto' => TipoGasto::optionsTipoGasto($subtipogasto->tipogasto_id),
            ];
        });

        $data = [
            'datatable' => $subtipogasto,
            'options' => [
                'optionsGasto' => gasto::optionsGasto(),
                'optionsTipoGasto' => TipoGasto::optionsTipoGasto(),
            ],



        ];


        return Inertia::render('Modulos/Costos/Parametros/Productos/index', $data);
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
    public function store(StoresubtipogastoRequest $request)
    {
        if (is_null($request->id)) {
            $subtipogasto = new subtipogasto();
        } else {
            $subtipogasto = subtipogasto::find($request->id);
        }

        $subtipogasto->codigo = $request->codigo;
        $subtipogasto->codigo1 = $request->codigo;


        $subtipogasto->nombre = $request->subtipogasto;
        $subtipogasto->gasto_id = $request->gasto['id'];

        $subtipogasto->tipogasto_id = $request->tipogasto['id'];
        $subtipogasto->save();

        return redirect(route('costos.param.subtipogasto.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(subtipogasto $subtipogasto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(subtipogasto $subtipogasto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatesubtipogastoRequest $request, subtipogasto $subtipogasto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($subtipogasto)
    {
        $subtipogasto = subtipogasto::find($subtipogasto)->delete();
        return redirect(route('costos.param.subtipogasto.index'));
    }
}
