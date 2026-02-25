<?php

namespace App\Http\Controllers\Modulos\Cumplidos\CumplidoLaborCampo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modulos\Cumplidos\CumplidoLaborCampo\Storecumplido_laborcampodetallecuadrillaRequest;
use App\Http\Requests\Modulos\Cumplidos\CumplidoLaborCampo\Updatecumplido_laborcampodetallecuadrillaRequest;
use App\Models\cumplido_laborcampo;
use App\Models\cumplido_laborcampodetallecuadrilla;
use App\Models\cumplido_laborcampodetallelote;

use function Laravel\Prompts\select;

class CumplidoLaborcampodetallecuadrillaController extends Controller
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
    public function store(Storecumplido_laborcampodetallecuadrillaRequest $request)
    {
        $CumplidoLaborCampo  = cumplido_laborcampo::find($request->CumplidoLaborCampo);

        $cumplido_laborcampodetallecuadrilla = new cumplido_laborcampodetallecuadrilla();
        $cumplido_laborcampodetallecuadrilla->cump_laborcampo_id = $CumplidoLaborCampo->id;
        $cumplido_laborcampodetallecuadrilla->personal_id = $request->empleado['id'];
        $cumplido_laborcampodetallecuadrilla->cantidad = $CumplidoLaborCampo->cantidad;

        $cumplido_laborcampodetallecuadrilla->save();

        $this->ActualizarCantidad($CumplidoLaborCampo->id);

        return json_encode($cumplido_laborcampodetallecuadrilla);
    }

    public function ActualizarCantidad($CumplidoLaborCampo)
    {
        $CumplidoLaborCampo  = cumplido_laborcampo::find($CumplidoLaborCampo);

        $cantidad_total = $CumplidoLaborCampo->cantidadtotal;
        $costo_unit = $CumplidoLaborCampo->Labor->CostoHect;


        $cumplido_laborcampodetallecuadrillas = cumplido_laborcampodetallecuadrilla::where('cump_laborcampo_id', $CumplidoLaborCampo->id)->get();

        if($cumplido_laborcampodetallecuadrillas->count() == 0){
            return;
        }
        $cantidad_por_detalle = $cantidad_total / $cumplido_laborcampodetallecuadrillas->count();

        foreach ($cumplido_laborcampodetallecuadrillas as $detalle) {
            $detalle->cantidad = $cantidad_por_detalle;
            $detalle->costo_unit = $costo_unit;
            $detalle->total_bonificacion = $cantidad_por_detalle *  $costo_unit;

            $detalle->save();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(cumplido_laborcampodetallecuadrilla $cumplido_laborcampodetallecuadrilla)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cumplido_laborcampodetallecuadrilla $cumplido_laborcampodetallecuadrilla)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatecumplido_laborcampodetallecuadrillaRequest $request, cumplido_laborcampodetallecuadrilla $cumplido_laborcampodetallecuadrilla)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cuadrilla = cumplido_laborcampodetallecuadrilla::find($id);

        $CumplidoLaborCampo = cumplido_laborcampo::find($cuadrilla->cump_laborcampo_id)->id;

        $cuadrilla->delete();

        $this->ActualizarCantidad($CumplidoLaborCampo);


        return redirect(route('CumpLaborCampo.edit', $CumplidoLaborCampo));
    }
}
