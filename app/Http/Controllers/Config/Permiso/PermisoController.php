<?php

namespace App\Http\Controllers\Config\Permiso;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermisoController extends Controller
{
    public function index()
    {
        // Consulta

        $data = Permission::select('id', 'name')->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'name' => $data->name,
            ];
        });
        return Inertia::render('Config/Permisos/Explorar', [
            'dataPermisos' => $data,
        ]);
    }


    public function create()
    {
        return Inertia::render('Config/Permisos/Crear', []);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|unique:roles|max:15',
        ]);

        $role = Permission::create(['name' => $request->name]);

        return redirect()->route('Permisos.Explorar')->with('success', 'Usuario creado correctamente');
    }

    public function edit(string $id)
    {

        // Verificar permisos manualmente en el controlador
        if (! Gate::allows('Usuarios.Editar')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        } else {
            // Consulta
            $User = Permission::where('id', $id)->firstOrFail();

            //Renderizar Vista
            return Inertia::render('Config/Permisos/Edit', [
                'datos' => [
                    'id' => $User->id,
                    'name' => $User->name,
1                ]
            ]);
        }
    }

    public function update(Request $request,  $id)
    {
        // Actualizar
        $Roledata = Permission::where('id', $id)->firstOrFail();
        $Roledata->name = $request->name;

        $Roledata->save();

        return redirect(route('Permisos.edit', $Roledata->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy( $id)
    {
        //
        $data =  Permission::where('id', $id)->firstOrFail();
        $data->delete();
        return redirect(route('Permisos.Explorar'));
    }
}
