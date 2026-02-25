<?php

namespace App\Http\Controllers\Modulos\Potreros;

use App\Http\Controllers\Controller;
use App\Models\Potrero;
use App\Http\Requests\StorePotreroRequest;
use App\Http\Requests\UpdatePotreroRequest;
use Illuminate\Http\Request;


class PotreroController extends Controller
{


    public function getOptionsPotrero(Request $request)
    {
        $data = Potrero::select(['id', 'nombre'])
            ->where('finca_id', $request['id'])
            ->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'label' => $data->nombre,
                ];
            });
        return response()->json($data);
    }


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
    public function store(StorePotreroRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Potrero $potrero)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Potrero $potrero)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePotreroRequest $request, Potrero $potrero)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Potrero $potrero)
    {
        //
    }
}
