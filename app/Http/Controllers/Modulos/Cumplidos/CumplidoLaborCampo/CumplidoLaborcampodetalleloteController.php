<?php

namespace App\Http\Controllers\Modulos\Cumplidos\CumplidoLaborCampo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modulos\Cumplidos\CumplidoLaborCampo\Storecumplido_laborcampodetalleloteRequest;
use App\Http\Requests\Modulos\Cumplidos\CumplidoLaborCampo\Updatecumplido_laborcampodetalleloteRequest;
use App\Models\cumplido_laborcampo;
use App\Models\cumplido_laborcampodetallelote;


class CumplidoLaborcampodetalleloteController extends Controller
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
    public function store(Storecumplido_laborcampodetalleloteRequest $request)
    {
        //
        $CumplidoLaborCampo  = cumplido_laborcampo::find($request->CumplidoLaborCampo);

        $cumplido_laborcampodetallelote = new cumplido_laborcampodetallelote();
        $cumplido_laborcampodetallelote->cump_laborcampo_id = $CumplidoLaborCampo->id;
        $cumplido_laborcampodetallelote->reg_lote_id = $request->RegLote_id;


        $cumplido_laborcampodetallelote->save();

        $this->ActualizarCantidad($CumplidoLaborCampo->id);
    }

    public function ActualizarCantidad($CumplidoLaborCampo)
    {
        $CumplidoLaborCampo  = cumplido_laborcampo::find($CumplidoLaborCampo);

        $cantidad_total = $CumplidoLaborCampo->cantidadtotal;
        $costo_unit = $CumplidoLaborCampo->Labor->CostoHect;


        $cumplido_laborcampodetallelote = cumplido_laborcampodetallelote::with('RegLote')->where('cump_laborcampo_id', $CumplidoLaborCampo->id)->get();

        if($cumplido_laborcampodetallelote->count() == 0){
            return;
        }

        $totalHect = $cumplido_laborcampodetallelote->sum(function ($detalle) {
            return $detalle->RegLote->Hect;
        });

        $cantidad_por_detalle = $cantidad_total / $totalHect;

        foreach ($cumplido_laborcampodetallelote as $detalle) {
            $detalle->cantidad = $cantidad_por_detalle * $detalle->RegLote->Hect;
            $detalle->save();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(cumplido_laborcampodetallelote $cumplido_laborcampodetallelote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cumplido_laborcampodetallelote $cumplido_laborcampodetallelote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatecumplido_laborcampodetalleloteRequest $request, cumplido_laborcampodetallelote $cumplido_laborcampodetallelote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $cumplido_laborcampodetallelote = cumplido_laborcampodetallelote::find($id);

        $CumplidoLaborCampo = cumplido_laborcampo::find($cumplido_laborcampodetallelote->cump_laborcampo_id)->id;

        $cumplido_laborcampodetallelote->delete();

        $this->ActualizarCantidad($CumplidoLaborCampo);


        return redirect(route('CumpLaborCampo.edit', $CumplidoLaborCampo));
    }
}
