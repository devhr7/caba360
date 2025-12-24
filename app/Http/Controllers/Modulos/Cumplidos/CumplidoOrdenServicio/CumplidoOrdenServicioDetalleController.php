<?php

namespace App\Http\Controllers\Modulos\Cumplidos\CumplidoOrdenServicio;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modulos\Cumplidos\CumplidoOrdenServicio\StoreCumplidoOrdenServicioDetalleRequest;
use App\Http\Requests\Modulos\Cumplidos\CumplidoOrdenServicio\UpdateCumplidoOrdenServicioDetalleRequest;
use App\Models\CumplidoOrdenServicio;
use App\Models\CumplidoOrdenServicioDetalle;
use App\Models\RecordVisita;

class CumplidoOrdenServicioDetalleController extends Controller
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
    public function store(StoreCumplidoOrdenServicioDetalleRequest $request, $cumplidoOrdenServicio)
    {

        $cumplidoOrdenServicio = CumplidoOrdenServicio::where('slug', $cumplidoOrdenServicio)->first();




        $cumplidoOrdenServicioDetalle = CumplidoOrdenServicioDetalle::create([
            'CumplidoOrdenServicio_id' => $cumplidoOrdenServicio->id,
            'TipoCentroCosto_id' => $request->claseCentroCosto['id'],
            'finca_id' => is_null($request->finca) ? null : $request->finca['id'],
            'DestinoServicio' => $request->destino,
            'Lote_id' => is_null($request->lote) ? null : $request->lote['id'],
            'potrero_id' => is_null($request->potrero) ? null : $request->potrero['id'],
            'RegLote_id' => $request->RegLote_id,
            'Labor_id' => is_null($request->labor) ? null : $request->labor['id'],
            'DetalleLabor' => $request->DetalleLabor,
            'Cantidad' => $request->cantidad,
            'UnidadMedida_id' => $request->unidadMedida['id'],
            'ValorUnit' => $request->PrecioUnit,
            'Total' => doubleval($request->PrecioUnit) * doubleval($request->cantidad),

            'RecordVisita' => is_array($request->record) ? $request->record['Codigo'] : $request->record,
            'RecordVisita_id' => is_array($request->record) ? $request->record['id'] :null,
            'Observaciones' => $request->observaciones,
        ]);

        //return $cumplidoOrdenServicio;

        return redirect(route('CumplidoOrdenServicio.edit', $cumplidoOrdenServicio->slug))->with('success', 'Cumplido Aplicacion Detalle Creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(CumplidoOrdenServicioDetalle $cumplidoOrdenServicioDetalle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CumplidoOrdenServicioDetalle $cumplidoOrdenServicioDetalle)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCumplidoOrdenServicioDetalleRequest $request, $cumplidoOrdenServicio)
    {
        //
        $cumplidoOrdenServicioDetalle = CumplidoOrdenServicioDetalle::find($cumplidoOrdenServicio);

        $cumplidoOrdenServicioDetalle->TipoCentroCosto_id = $request->claseCentroCosto['id'];
        $cumplidoOrdenServicioDetalle->finca_id = is_null($request->finca) ? null : $request->finca['id'];
        $cumplidoOrdenServicioDetalle->potrero_id = is_null($request->potrero) ? null : $request->potrero['id'];
        $cumplidoOrdenServicioDetalle->DestinoServicio = $request->destino;
        $cumplidoOrdenServicioDetalle->Lote_id = is_null($request->lote) ? null : $request->lote['id'];
        $cumplidoOrdenServicioDetalle->RegLote_id = $request->RegLote_id;
        $cumplidoOrdenServicioDetalle->Labor_id = is_null($request->labor) ? null : $request->labor['id'];
        $cumplidoOrdenServicioDetalle->DetalleLabor = $request->DetalleLabor;
        $cumplidoOrdenServicioDetalle->Cantidad = $request->cantidad;
        $cumplidoOrdenServicioDetalle->UnidadMedida_id = $request->unidadMedida['id'];
        $cumplidoOrdenServicioDetalle->ValorUnit = $request->PrecioUnit;
        $cumplidoOrdenServicioDetalle->Total = doubleval($request->PrecioUnit) * doubleval($request->cantidad);
        $cumplidoOrdenServicioDetalle->Observaciones = $request->observaciones;
        $cumplidoOrdenServicioDetalle->RecordVisita = is_array($request->record) ? $request->record['Codigo'] : $request->record;
        $cumplidoOrdenServicioDetalle->RecordVisita_id = is_array($request->record) ? $request->record['id'] :null;

        $cumplidoOrdenServicioDetalle->save();

        return redirect(route('CumplidoOrdenServicio.edit', $cumplidoOrdenServicioDetalle->CumplidoOrdenServicio->slug))->with('success', 'Cumplido Aplicacion Detalle actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($CumplidoOrdenServicioDetalle)
    {

        $cumplidoOrdenServicioDetalle = CumplidoOrdenServicioDetalle::find($CumplidoOrdenServicioDetalle);
        $cumplidoOrdenServicio_slug = $cumplidoOrdenServicioDetalle->CumplidoOrdenServicio->slug;
        $cumplidoOrdenServicioDetalle->delete();

        return redirect(route('CumplidoOrdenServicio.edit', $cumplidoOrdenServicio_slug))->with('success', 'Cumplido Aplicacion acualizado correctamente');
    }
}
