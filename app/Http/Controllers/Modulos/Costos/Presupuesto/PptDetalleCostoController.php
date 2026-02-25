<?php

namespace App\Http\Controllers\Modulos\Costos\Presupuesto;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modulos\Costos\Presupuesto\Storeppt_detalle_costoRequest;
use App\Http\Requests\Modulos\Costos\Presupuesto\Updateppt_detalle_costoRequest;
use App\Models\ppt_detalle_costo;


class PptDetalleCostoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Storeppt_detalle_costoRequest $request)
    {
        //
        // ['ppt_id', 'finca_id', 'gasto_id', 'costoxhect'];
        $ppt_detalle_costo = new ppt_detalle_costo();
        $ppt_detalle_costo->ppt_id = $request->ppt_id;
        $ppt_detalle_costo->finca_id = $request->finca['id'];
        $ppt_detalle_costo->gasto_id = $request->gasto['id'];
        $ppt_detalle_costo->costoxhect = $request->costoxhect;
        $ppt_detalle_costo->save();

        return redirect()->route('costos.ppt.edit', $ppt_detalle_costo->ppt_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(ppt_detalle_costo $ppt_detalle_costo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ppt_detalle_costo $ppt_detalle_costo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updateppt_detalle_costoRequest $request, ppt_detalle_costo $ppt_detalle_costo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ppt_detalle_costo)
    {
        $ppt_detalle_costo = ppt_detalle_costo::findOrFail($ppt_detalle_costo);
        $ppt_id = $ppt_detalle_costo->ppt_id;
        $ppt_detalle_costo->delete();

        return redirect()->route('costos.ppt.edit', $ppt_id);
    }
}
