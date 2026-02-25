<?php

namespace App\Http\Controllers\Modulos\Costos\Presupuesto;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modulos\Costos\Presupuesto\Storeppt_generalRequest;
use App\Http\Requests\Modulos\Costos\Presupuesto\Updateppt_generalRequest;
use App\Models\Finca;
use App\Models\gasto;
use App\Models\ppt_detalle_costo;
use App\Models\ppt_general;

use App\Models\status;
use Carbon\Carbon;
use Inertia\Inertia;

class PptGeneralController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $ppt_general = ppt_general::all()->map(function ($reg) {
            return [
                'id' => $reg->id,
                'slug' => $reg->slug,
                'fecha_ini' => $reg->fecha_ini,
                'fecha_fin' => $reg->fecha_fin,
                'status' => $reg->status->status,
            ];
        });

        $data = [
            'datatable' => $ppt_general,
        ];
        return Inertia::render('Modulos/Costos/Presupuesto/index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $data = [
            'options' => [
                'status' => status::optionsStatus()
            ],
        ];
        return Inertia::render('Modulos/Costos/Presupuesto/create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Storeppt_generalRequest $request)
    {
        //

        $ppt_general = new ppt_general();

        //$ppt_general->slug = $request->slug;
        $ppt_general->status_id = $request->status['id'];
        $ppt_general->fecha_ini =  empty($request->fecha_ini) ? null :  Carbon::parse($request->fecha_ini)->format('Y-m-d');
        $ppt_general->fecha_fin =  empty($request->fecha_fin) ? null :  Carbon::parse($request->fecha_fin)->format('Y-m-d');
        $ppt_general->save();
        return redirect()->route('costos.ppt.edit', $ppt_general->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(ppt_general $ppt_general)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ppt_general)
    {
        $ppt_general = ppt_general::findOrFail($ppt_general);

        if (!$ppt_general) {
            return redirect()->route('ppt_general.index');
        }

        $ppt_detalle_costos = ppt_detalle_costo::where('ppt_id', $ppt_general->id)->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'finca' => $item->finca->finca,
                'gasto' => $item->gasto->nombre,
                'costoxhect' => number_format(doubleval($item->costoxhect), 0, ",", "."),
                'costoxhectsf' => $item->costoxhect,
            ];
        });

        $data = [
            'data' => [
                'id' => $ppt_general->id,
                'status' => status::optionsStatus($ppt_general->status_id),
                'fecha_ini' => $ppt_general->fecha_ini,
                'fecha_fin' => $ppt_general->fecha_fin,
            ],
            'datatable' => [
                'ppt_detalle_costos' => $ppt_detalle_costos,
            ],
            'options' => [
                'status' => status::optionsStatus(),
                'get_finca' => Finca::optionsFinca(),
                'get_gasto' => gasto::optionsGasto(),
            ],
        ];
        return Inertia::render('Modulos/Costos/Presupuesto/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updateppt_generalRequest $request, ppt_general $ppt_general)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ppt_general $ppt_general)
    {
        //
    }
}
