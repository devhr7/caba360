<?php

namespace App\Http\Controllers\Modulos\Cumplidos\CumplidoAplicacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modulos\Cumplidos\CumplidoAplicacion\StoreCumplidoAplicacionProductoRequest;
use App\Http\Requests\Modulos\Cumplidos\CumplidoAplicacion\UpdateCumplidoAplicacionProductoRequest;
use App\Models\CumplidoAplicacion;
use App\Models\CumplidoAplicacionProducto;
use App\Models\Labor;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


use function PHPUnit\Framework\isNull;

class CumplidoAplicacionProductoController extends Controller
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
    public function store(StoreCumplidoAplicacionProductoRequest $request, $slug)
    {
        //
        $CumplidoAplicacion = CumplidoAplicacion::where('slug', $slug)->firstOrFail();

        $CumplidoAplicacionProducto = CumplidoAplicacionProducto::create([
            'CumplidoAplicacion_id' => $CumplidoAplicacion->id,
            'GrupoMateriaPrima_id' => $request->TipoProducto['id'],
            'producto_id' => $request->Producto['id'],
            'Dosis_por_Ha' => $request->CantxHect,
            'cantidad_Total' => $request->Total,
            'PrecioUnit' => $request->Producto['PrecioUnit'],
            'PrecioTotal' => doubleval($request->Total) * doubleval($request->Producto['PrecioUnit']),
        ]);

        $CumplidoAplicacionLabor = CumplidoAplicacionProducto::where('CumplidoAplicacion_id', $CumplidoAplicacion->id)->whereNotNull('labor_id')->firstOrFail();



        $CumplidoAplicacionLabor->save();

        $this->ActualizarValorLabor($CumplidoAplicacion->id);
    }


    /** Store servicios */

    public function storeServicios(Request $request, $slug)
    {
        $validated = $request->validate([
            'labor' => ['required', 'array'],
            'labor.id' => ['required', 'integer', 'exists:labors,id'],
        ]);

        $cumplidoAplicacionProducto = CumplidoAplicacionProducto::findOrFail($slug);
        $CumplidoAplicacion = CumplidoAplicacion::findOrFail($cumplidoAplicacionProducto->CumplidoAplicacion_id);

        $cumplidoAplicacionProducto->labor_id = (int) $validated['labor']['id'];

        $cumplidoAplicacionProducto->save();

        $this->ActualizarValorLabor($CumplidoAplicacion->id);

        return redirect(route('CumplidoAplicacion.edit', $CumplidoAplicacion->slug))->with('success', 'Cumplido Aplicacion acualizado correctamente');
    }


    /**
     * Display the specified resource.
     */
    public function show(CumplidoAplicacionProducto $cumplidoAplicacionProducto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CumplidoAplicacionProducto $cumplidoAplicacionProducto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCumplidoAplicacionProductoRequest $request, CumplidoAplicacionProducto $cumplidoAplicacionProducto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($CumplidoAplicacionProducto)
    {
        // Verificar permisos manualmente en el controlador

        if (! Gate::allows('mod.cump_aplicacion.delete')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        $CumplidoAplicacionProductos =  CumplidoAplicacionProducto::where('id', $CumplidoAplicacionProducto)->firstOrFail();
        $CumplidoAplicacion = $CumplidoAplicacionProductos->CumplidoAplicacion_id;
        if (!in_array($CumplidoAplicacionProductos->labor_id, Labor::where('CumpApliV', 1)->pluck('id')->toArray())) {
            $CumplidoAplicacionProductos->delete();
            $this->ActualizarValorLabor($CumplidoAplicacion);
        } else {
            return back()->with('error', 'No se puede eliminar este registro');
        }
    }


    public function AddProductosPorRecord(Request $request)
    {

        if (isset($request->labor) || isset($request->CumplidoAplicacion) || isset($request->Record)) {
            if ($request->labor['label'] == "Fertilizacion Aerea" || $request->labor['label'] == "Fertilizacion Terrestre" || $request->labor['label'] == "Fertilizacion Dirigida") {
                $CumplidoAplicacion = CumplidoAplicacion::where('slug', $request->CumplidoAplicacion)->firstOrFail();
                $CumplidoAplicacionLabor = CumplidoAplicacionProducto::where('CumplidoAplicacion_id', $CumplidoAplicacion->id)->delete();

                return $CumplidoAplicacionLabor;


                return "Es una fertilizacion";
            } else {

                return "Es una Aplicacion";
            }
        }
    }

    public function ActualizarValorLabor($CumplidoAplicacion_id)
    {
        $CumplidoAplicacionDetalle = CumplidoAplicacionProducto::where('CumplidoAplicacion_id', $CumplidoAplicacion_id)->whereNotNull('labor_id')->firstOrFail();
        $CumplidoAplicacion = CumplidoAplicacion::where('id', $CumplidoAplicacion_id)->firstOrFail();

        $Hectareas = $CumplidoAplicacion->HectLote;
        $PrecioUnit = Labor::where('id', $CumplidoAplicacionDetalle->labor_id)->firstOrFail()->CostoHect;


        /**
         * *Aplicacion
         */
        if (in_array($CumplidoAplicacionDetalle->labor_id, Labor::where('Hect', 1)->pluck('id')->toArray())) { // Aplicacion
            $cantidadTotal = doubleval($Hectareas); // Las Cantidades de la Aplicacion es igual a las Hectareas


            //Fin Condicion

            /**
             * *Fertilizacion
             */
        } elseif (in_array($CumplidoAplicacionDetalle->labor_id, Labor::where('Hect', 0)->pluck('id')->toArray())) { // Fertilizacion
            $cantidadTotal = CumplidoAplicacionProducto::where('CumplidoAplicacion_id', $CumplidoAplicacion_id)
                ->whereNotNull('producto_id')
                ->sum('cantidad_Total');
        }

        // Guarda y Actualiza la Labor.
        $CumplidoAplicacionDetalle->cantidad_Total = $cantidadTotal;
        $CumplidoAplicacionDetalle->PrecioUnit = $PrecioUnit;
        $CumplidoAplicacionDetalle->PrecioTotal = $cantidadTotal * $PrecioUnit;
        $CumplidoAplicacionDetalle->save();
    }
}
