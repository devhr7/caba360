<?php

namespace App\Http\Controllers\Config\Usuario;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Show the list of all users
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Consulta

        $data = User::select('id', 'identificacion', 'name', 'email')->whereNotIn('identificacion', [1997, 123])->get()->map(function ($data) {


            return [
                'id' => $data->id,
                'identificacion' => (string)$data->identificacion,
                'name' => $data->name,
                'email' => $data->email,
                'edit_url' => route('User.edit', $data->identificacion),
            ];
        });
        return Inertia::render('Config/Usuario/Explorar', [
            'dataUser' => $data,
        ]);
    }

    public function create()
    {
        return Inertia::render('Config/Usuario/Crear', []);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'identificacion' => 'required|unique:users|integer',
            'name' => 'required|unique:users|max:15',
            'email' => 'required|email|unique:users|max:100',
        ]);


        $User = User::create([
            'identificacion' => $request->identificacion,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->identificacion)
        ]);

        return redirect()->route('User.Explorar')->with('success', 'Usuario creado correctamente');
    }


    /**
     * Muestra la vista para Editar un Cumplido de Aplicacion
     *
     * @param int $CumplidoAplicacion Identificador del Cumplido de Aplicacion
     *
     * @return \Inertia\Response
     */
    public function edit(string $identificacion)
    {

        // Verificar permisos manualmente en el controlador
        if (! Gate::allows('config.user.show')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        } else {
            // Consulta
            $User = User::where('identificacion', $identificacion)->firstOrFail();

            //Renderizar Vista
            return Inertia::render('Config/Usuario/Edit', [
                'datos' => [
                    'id' => $User->id,
                    'identificacion' => $User->identificacion,
                    'name' => $User->name,
                    'email' => $User->email,
                ]
            ]);
        }
    }

    public function update(Request $request,  $identificacion)
    {
        // Actualizar
        $Userdata = User::where('identificacion', $identificacion)->firstOrFail();
        $Userdata->identificacion = $request->identificacion;
        $Userdata->name = $request->name;
        $Userdata->email = $request->email;


        $Userdata->save();

        return redirect(route('User.edit', $request->identificacion));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy( $identificacion)
    {
        //
        $data =  User::where('identificacion', $identificacion)->firstOrFail();
        $data->delete();
        return redirect(route('User.Explorar'));
    }

    public function PasswordReset( $identificacion)
    {
        //
        $dataUser =  User::where('identificacion', $identificacion)->firstOrFail();
        $dataUser->password = Hash::make($identificacion);
        $dataUser->save();

        return redirect(route('User.edit', $identificacion));

    }
}

