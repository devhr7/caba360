<?php

namespace App\Http\Controllers\Config\Rol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    //

    /**
     * Muestra una lista de los roles que no sean Super-Admin.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Consulta
        if (! Gate::allows('config.rol.show')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        $data = Role::select('id', 'name')->whereNotIn('name', ['Super-Admin'])->get()->map(function ($data) {

            return [
                'id' => $data->id,
                'name' => $data->name,
            ];
        });
        return Inertia::render('Config/Rol/Explorar', [
            'dataRol' => $data,
        ]);
    }


    public function create()
    {
        if (! Gate::allows('config.rol.create')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        return Inertia::render('Config/Rol/Crear', []);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|unique:roles|max:15',
        ]);

        $role = Role::create(['name' => $request->name]);

        return redirect()->route('Rol.Explorar')->with('success', 'Usuario creado correctamente');
    }

    public function edit(string $id)
    {

        // Verificar permisos manualmente en el controlador
        if (! Gate::allows('config.rol.show')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        } else {
            // Consulta
            $Role = Role::where('id', $id)->firstOrFail();


            //Renderizar Vista
            return Inertia::render('Config/Rol/Edit', [
                'datos' => [
                    'id' => $Role->id,
                    'name' => $Role->name,

                ],
                'todos_permisos' => $Role->permissions()->get()->map(function($item){
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description,
                    ];
                }),
                'permisos_rol'=> $Role->getAllPermissions()->map(function($item){
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description,
                    ];
                }),
                'dt_permisos'=> Permission::whereNotIn('name', $Role->getAllPermissions()->pluck('name'))->orderBy('name')->get()->map(function($item){
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description,

                    ];
                }),

            ]);
        }
    }

    public function update(Request $request,  $id)
    {
        // Actualizar
        $Roledata = Role::where('id', $id)->firstOrFail();
        $Roledata->name = $request->name;

        $Roledata->save();

        return redirect(route('Rol.edit', $Roledata->id));
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
        $data =  Role::where('id', $id)->firstOrFail();
        $data->delete();
        return redirect(route('Rol.Explorar'));
    }

/**
 * GestionPermisos function is responsible for managing permissions.
 *
 * @param request The request object
 */
    public function GestionPermisos(Request $request, $rol) {

        if (! Gate::allows('config.rol.gestionpermisos')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        $Role = Role::where('id', $rol)->firstOrFail();
        //$Role->syncPermissions($request->permisos[1]->name);
       $permisos = collect($request->permisos[1])->pluck('name');

        $Role->syncPermissions($permisos);

        return redirect(route('Rol.edit', $Role->id));
    }

}
