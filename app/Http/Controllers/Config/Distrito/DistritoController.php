<?php

namespace App\Http\Controllers\Config\Distrito;

use App\Http\Controllers\Controller;
use App\Http\Requests\Config\Distrito\StoreDistritoRequest;
use App\Http\Requests\Config\Distrito\UpdateDistritoRequest;
use App\Mail\TestMail;
use App\Models\Distrito;
use Illuminate\Support\Facades\Mail;

use Inertia\Inertia;

class DistritoController extends Controller
{
    /**
     * Vista de la tabla Inicio Explorar.
     */
    public function index()
    {
        // Consulta
        $distrito = Distrito::all()->map(function ($data) {
            return [
                'id' => $data->id,
                'distrito' => $data->distrito,
                'edit_url' => route('Distrito.edit', $data->slug),

            ];
        });

        //Renderizar Vista
        return Inertia::render('Config/Distrito/Explorar', [
            'distrito' => $distrito,
            'create_url' => route('Distrito.Crear'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return Inertia::render('Config/Distrito/Crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDistritoRequest $request)
    {
        //
        $Distrito = new Distrito;
        $Distrito->distrito = $request->distrito;
        $Distrito->save();


        return redirect(route('Distrito.Explorar'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Distrito $distrito)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        // Consulta
        $distrito = Distrito::where('slug', $slug)->firstOrFail();

        //Renderizar Vista
        return Inertia::render('Config/Distrito/Edit', [
            'datos' => [
                'id' => $distrito->id,
                'distrito' => $distrito->distrito, // Asegúrate de usar el campo correcto
                'slug' => $distrito->slug,
                'update_url' => route('Distrito.update', $distrito->slug),
                'delete_url' => route('Distrito.delete', $distrito->slug),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDistritoRequest $request, string $slug)
    {
        //
        $Distrito = Distrito::where('slug', $slug)->firstOrFail();
        $Distrito->distrito = $request->distrito;
        $Distrito->save();

        return redirect(route('Distrito.edit', $Distrito->slug));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        //
        $Distrito =  Distrito::where('slug', $slug)->firstOrFail();
        $Distrito->delete();
        return redirect(route('Distrito.Explorar'));
    }
}
