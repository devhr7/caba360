<?php

namespace App\Http\Controllers\Modulos\Records\RecordVisita;


use App\Http\Controllers\Controller;
use App\Http\Requests\Modulos\Agronomo\RecordVisita\StoreProductoRecordVisitasRequest;
use App\Http\Requests\Modulos\Agronomo\RecordVisita\UpdateProductoRecordVisitasRequest;
use App\Models\ProductoRecordVisitas;
use App\Models\RecordVisita;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;


class ProductoRecordVisitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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
    public function store(StoreProductoRecordVisitasRequest $request,$slug)
    {
        $RecordVisita = RecordVisita::where('slug', $slug)->firstOrFail();

        // Validar que el producto no est 2 veces en el record de visita
        $ProductoRecordVisitas = ProductoRecordVisitas::where('RecordVisita_id', $RecordVisita->id)
            ->where('producto_id', $request->Producto['id'])
            ->first();
        if ($ProductoRecordVisitas) {
            return back()->withErrors(['Producto' => 'El producto ya est  en este record de visita']);
        }

        $ProductoRecordVisitas = ProductoRecordVisitas::create([
        'RecordVisita_id' => $RecordVisita->id,
        'GrupoMateriaPrima_id' => $request->TipoProducto['id'],
        'producto_id' => $request->Producto['id'],
        'Dosis_por_Ha' => $request->CantxHect,
        'cantidad_Total'=> $request->Total,
        'fecha_estimada_aplicacion' => empty($request->FechaAplicacion) ? null :  Carbon::parse($request->FechaAplicacion)->format('Y-m-d'),
        ]);

        //return redirect()->route('RecordVisita.edit', $ProductoRecordVisitas->slug)->with('success', 'Record Visita Agronomo creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductoRecordVisitas $productoRecordVisitas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductoRecordVisitas $productoRecordVisitas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRecordVisitasRequest $request, ProductoRecordVisitas $productoRecordVisitas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($productoRecordVisitas)
    {
        //
        // Verificar permisos manualmente en el controlador
        if (! Gate::allows('mod.recordvisita.delete')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        $ProductoRecordVisitas =  ProductoRecordVisitas::where('id', $productoRecordVisitas)->firstOrFail();
        $ProductoRecordVisitas->delete();
        //return redirect(route('CumpMaquinaria.delete'));

    }
}
