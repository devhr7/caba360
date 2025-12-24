<?php

namespace App\Http\Controllers\Config\GrupoMateriaPrima;

use App\Http\Controllers\Controller;
use App\Http\Requests\Config\GrupoMateriaPrima\StoreGrupo_MateriaPrimaRequest;
use App\Models\Grupo_MateriaPrima;
use App\Http\Requests\Config\GrupoMateriaPrima\UpdateGrupo_MateriaPrimaRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;



class GrupoMateriaPrimaController extends Controller
{


    /**
     * Verificar permisos manualmente en el controlador, solo para explorar.
     *
     * @return void
     */
    public function __construct()
    {
        // Verificar permisos manualmente en el controlador
        if (! Gate::allows('CumplidoMaquinaria.Explorar')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verificar permisos manualmente en el controlador
        if (! Gate::allows('CumplidoMaquinaria.Explorar')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        // Consulta
        $Grupo_MateriaPrima = Grupo_MateriaPrima::select(['id', 'GrupoMateriaPrima', 'slug'])->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'GrupoMPrima' => $data->GrupoMateriaPrima,
                'edit_url' => route('GrupoMateriaPrima.edit', $data->slug),
            ];
        });

        //Renderizar Vista
        return Inertia::render('Config/GrupoMateriaPrima/Explorar', [
            'data' => $Grupo_MateriaPrima,
            'create_url' => route('GrupoMateriaPrima.Crear'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar permisos manualmente en el controlador
        if (! Gate::allows('Param_GrupoMateriaPrima.Crear')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        //Renderizar Vista
        return Inertia::render('Config/GrupoMateriaPrima/Crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGrupo_MateriaPrimaRequest $request): RedirectResponse
    {
        //$slug = Str::slug($request->nombre, '-');
        $grupoMateriaPrima = Grupo_MateriaPrima::create([
            'GrupoMateriaPrima' => $request->GrupoMateriaPrima,
        ]);
        return redirect()->route('GrupoMateriaPrima.Explorar')->with('success', 'Grupo Materia Prima creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grupo_MateriaPrima $grupo_MateriaPrima) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        // Verificar permisos manualmente en el controlador
        if (! Gate::allows('Param_GrupoMateriaPrima.Editar')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        //Renderizar Vista
        return Inertia::render(
            'Config/GrupoMateriaPrima/Edit',
            [
                'datos' => Grupo_MateriaPrima::where('slug', $slug)->firstOrFail(),
            ]
        );
    }

    /**
     * Actualizar el Grupo Materia Prima.
     *
     * @param UpdateGrupo_MateriaPrimaRequest $request
     * @param string $slug
     * @return RedirectResponse
     */
    public function update(UpdateGrupo_MateriaPrimaRequest $request, $slug) : RedirectResponse
    {
        // Buscar el Grupo Materia Prima por Slug
        $grupoMateriaPrima = Grupo_MateriaPrima::where('slug', $slug)->firstOrFail();

        // Actualizar el Grupo Materia Prima con los nuevos valores
        // GrupoMateriaPrima: es el nombre del grupo de materia prima
        // slug: es el slug del grupo de materia prima que se va a actualizar
        $grupoMateriaPrima->update([
            'GrupoMateriaPrima' => $request->GrupoMateriaPrima,
            'slug' => Str::of($request->GrupoMateriaPrima)->slug(),
        ]);

        // Verificar si el Grupo Materia Prima se actualizo correctamente
        if ($grupoMateriaPrima instanceof Grupo_MateriaPrima) {
            // Redirigir al usuario a la pantalla de editar con un mensaje de confirmacion
            return redirect(route('GrupoMateriaPrima.edit', $grupoMateriaPrima->slug))
                ->with('success', 'Grupo Materia Prima actualizado correctamente');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $slug
     * @return RedirectResponse
     */
    public function destroy($slug) : RedirectResponse
    {
        // Verificar permisos manualmente en el controlador
        // Si no se tiene permiso para eliminar, se lanza una excepcion de tipo 403
        if (! Gate::allows('Param_GrupoMateriaPrima.Eliminar')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        // Buscamos el registro en la base de datos con el slug proporcionado
        $data =  Grupo_MateriaPrima::where('slug', $slug)->firstOrFail();

        // Lo eliminamos
        $data->delete();

        // Redirigimos al usuario a la pantalla de explorar
        // con un mensaje de confirmacion
        return redirect()->route('GrupoMateriaPrima.Explorar')->with('success', 'Grupo Materia Prima eliminado correctamente');
    }


    public function OptionsMateriaPrimaScope(Request $request)
    {
        $grupoMPrimaId = $request->input('GrupoMPrima_id.id') ?? null;

        return response()->json(Grupo_MateriaPrima::optionsGrupoMateriaPrima($grupoMPrimaId));
    }

}
