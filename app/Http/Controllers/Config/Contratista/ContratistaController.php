<?php

namespace App\Http\Controllers\Config\Contratista;
use App\Http\Controllers\Controller;
use App\Models\contratista;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ContratistaController extends Controller
{
public function index()
    {
        // Consulta
        $contratistas = contratista::all()->map(function ($data) {
            return [
                'id' => $data->id,
                'identificacion' => $data->identificacion,
                'nombre' => $data->nombre,
                'slug' => $data->slug,
            ];
        });

        //Renderizar Vista

        return Inertia::render('Config/contratistas/Explorar', [
            'contratistas' => $contratistas,

        ]);
    }

    public function create()
    {
        //Renderizar Vista
        return Inertia::render('Config/contratistas/Crear');
    }
    public function store(Request $request)
    {
        // Validar Datos
        $request->validate([
            'identificacion' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
        ]);

        // Crear Contratista
        contratista::create([
            'identificacion' => $request->identificacion,
            'nombre' => $request->nombre,
        ]);

        // Redireccionar a la lista de contratistas
        return redirect()->route('contratista.Explorar')->with('success', 'Contratista creado exitosamente.');
    }
    public function edit(string $contratista)
    {
        // Buscar Contratista por Slug
        $contratista = contratista::where('slug', $contratista)->firstOrFail();
        // Verificar si el Contratista Existe
        if (!$contratista) {
            return redirect()->route('contratista.Explorar')->with('error', 'Contratista no encontrado.');
        }

        // Renderizar Vista
        return Inertia::render('Config/contratistas/Edit', [
            'contratista' => [
                'id' => $contratista->id,
                'identificacion' => $contratista->identificacion,
                'nombre' => $contratista->nombre,
                'slug' => $contratista->slug,
            ],
        ]);

    }
    public function update(Request $request, string $contratista)
    {
        // Validar Datos
        $request->validate([
            'identificacion' => 'required|integer',
            'nombre' => 'required|string|max:255',
        ]);
        // Buscar Contratista por Slug
        $contratista = contratista::where('slug', $contratista)->firstOrFail();
        // Verificar si el Contratista Existe
        if (!$contratista) {
            return redirect()->route('contratista.Explorar')->with('error', 'Contratista no encontrado.');
        }else {
            // Actualizar Contratista
            $contratista->update([
                'identificacion' => $request->identificacion,
                'nombre' => $request->nombre,
            ]);
        }
        // Redireccionar a la lista de contratistas
        return redirect()->route('contratista.Explorar')->with('success', 'Contratista actualizado exitosamente.');


    }
    public function destroy(string $contratista)
    {
        // Buscar Contratista por Slug
        $contratista = contratista::where('slug', $contratista)->firstOrFail();
        // Verificar si el Contratista Existe
        if (!$contratista) {
            return redirect()->route('contratista.Explorar')->with('error', 'Contratista no encontrado.');
        } else {
            // Eliminar Contratista
            $contratista->delete();
        }
        // Redireccionar a la lista de contratistas
        return redirect()->route('contratista.Explorar')->with('success', 'Contratista eliminado exitosamente.');

    }
}
