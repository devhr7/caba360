<?php

namespace App\Http\Controllers\Modulos\Empleado;

use App\Http\Controllers\Controller;
use App\Models\Empleados;
use App\Http\Requests\Modulos\Empleados\StoreEmpleadosRequest;
use App\Http\Requests\Modulos\Empleados\UpdateEmpleadosRequest;
use App\Models\Cargo;
use Inertia\Inertia;
use Illuminate\Support\Str;

class EmpleadosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //map y tr
        $empleados = Empleados::all()->map(function ($data) {
            return [
                'id' => $data->id,
                'estado' => ($data->FechaRetiro && $data->FechaRetiro < now()) ? 'Inactivo' : 'Activo',
                'identificacion' => $data->identificacion,
                'nombre1' => $data->nombre1 . ' ' . $data->nombre2 . ' ' . $data->apellido1 . ' ' . $data->apellido2,
                'cargo' => $data->cargo_id ? $data->Cargo->cargo : '',
                'selectedCargo' => Cargo::optionsCargo($data->cargo_id),

            ];
        });
        $options = [
            'cargos' => Cargo::optionsCargo(),
        ];

        return Inertia::render('Config/Empleados/index', [
            'empleados' => $empleados,
            'options' => $options,
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
    public function store(StoreEmpleadosRequest $request)
    {
        //

        $empleado = new Empleados();
        $empleado->slug = Str::slug($request->nombre);
        $empleado->identificacion = $request->identificacion;
        $empleado->nombre1 = $request->nombre;
        $empleado->cargo_id = $request->cargo['id'];
        $empleado->save();
        // retorna exitoso, y recarga la pagina
        return redirect()->back()->with('success', 'Empleado creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Empleados $empleados)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empleados $empleados)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmpleadosRequest $request, $id)
    {
        //
        $empleado = Empleados::find($id);
        $empleado->identificacion = $request->identificacion;
        $empleado->nombre1 = $request->nombre;
        $empleado->cargo_id = $request->cargo['id'];
        $empleado->save();



        // retorna exitoso, y recarga la pagina
        return redirect()->back()->with('success', 'Empleado actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $empleado = Empleados::find($id);
        $empleado->delete();
        return redirect()->back()->with('success', 'Empleado eliminado correctamente.');
    }
}
