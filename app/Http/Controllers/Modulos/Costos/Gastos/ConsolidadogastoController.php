<?php

namespace App\Http\Controllers\Modulos\Costos\Gastos;

use App\Models\consolidadogasto;

use App\Http\Requests\StoreconsolidadogastoRequest;
use App\Http\Requests\UpdateconsolidadogastoRequest;
use App\Http\Controllers\Controller;
use App\Models\RegistroLotes;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\subtipogasto;
use Carbon\Carbon;
use DateTime;

class ConsolidadogastoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function importar_movimientos() //vista
    {
        return inertia::render('Modulos/Costos/Gastos/Importar/index', []);
    }

    public function importar_movimientos_validacion(Request $request) //vista
    {
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Omitir la primera fila (encabezado)
        array_shift($rows);

        // Validar el contenido del archivo
        $valid = true;
        $data = [];
        foreach ($rows as $row) {
            if (count($row) <= 7) {
                $valid = false;
                break;
            }

            $RegLote = RegistroLotes::where('Codigo', $row[0])->first();
            $subtipogasto = subtipogasto::where('codigo', $row[1])->first();

            if (!$RegLote) {
                $valid = false;
                $data[] = [
                    'Lote' => $row[0],
                    'Codigo' => $row[1],
                    'Nombre' => $row[2],
                    'Comprobante' => $row[3],
                    'Cantidad' => $row[4],
                    'ValorUnitario' => $row[5],
                    'Total' => $row[6],
                    'Observaciones' => $row[7],
                    'Finca' => 'N/A',
                    'Error' => 'El lote no se encuentra en la base de datos',
                ];
                continue;
            }

            if (!$subtipogasto) {
                $valid = false;
                $data[] = [
                    'Lote' => $row[0],
                    'Finca' => $RegLote->finca->finca,
                    'Finca' => $RegLote->finca->finca,
                    'Codigo' => $row[1],
                    'Nombre' => $row[2],
                    'Comprobante' => $row[3],
                    'Cantidad' => $row[4],
                    'ValorUnitario' => $row[5],
                    'Total' => $row[6],
                    'Observaciones' => $row[7],
                    'Error' => 'El código no se encuentra en la base de datos',
                ];
                continue;
            }

            if (!is_numeric($row[4]) || !is_numeric($row[5]) || !is_numeric($row[6])) {
                $valid = false;
                $data[] = [
                    'Lote' => $row[0],
                    'Codigo' => $row[1],
                    'Nombre' => $row[2],
                    'Comprobante' => $row[3],
                    'Cantidad' => $row[4],
                    'ValorUnitario' => $row[5],
                    'Total' => $row[6],
                    'Observaciones' => $row[7],
                    'Finca' => $RegLote->finca->finca,
                    'Error' => 'Cantidad, Valor Unitario y Total deben ser valores numéricos',
                ];
                continue;
            }

            if (strlen($row[3]) > 100 || strlen($row[7]) > 100) {
                $valid = false;
                $data[] = [
                    'Lote' => $row[0],
                    'Codigo' => $row[1],
                    'Nombre' => $row[2],
                    'Comprobante' => $row[3],
                    'Cantidad' => $row[4],
                    'ValorUnitario' => $row[5],
                    'Total' => $row[6],
                    'Observaciones' => $row[7],
                    'Finca' => $RegLote->finca->finca,
                    'Error' => 'Comprobante y Observaciones deben ser menores a 100 caracteres',
                ];
                continue;
            }

            $data[] = [
                'fecha' =>
                DateTime::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d'),
                'Lote' => $row[0],
                'RegLote' => $RegLote->id,
                'Finca' => $RegLote->finca->finca,
                'lote_name' => $RegLote->Lote->lote,
                'Codigo' => $row[1],
                'gasto_id' => $subtipogasto->tipogasto->gasto_id,
                'gasto'=> $subtipogasto->tipogasto->gasto->nombre,
                'tipogasto_id' => $subtipogasto->tipogasto_id,
                'tipo_gasto' => $subtipogasto->tipogasto->nombre,
                'subtipogasto_id' => $subtipogasto->id,
                'subtipo_gasto' => $subtipogasto->nombre,
                'Nombre' => $row[2],
                'Comprobante' => $row[3],
                'Cantidad' => doubleval($row[4]),
                'ValorUnitario' => doubleval($row[5]),
                'Total' => doubleval($row[6]),
                'Observaciones' => $row[7],
                'Error' => '',
            ];
        }

        return response()->json(['success' => $valid, 'data' => $data]);
    }

    public function importar_movimientos_subir(Request $request) //vista
    {
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Omitir la primera fila (encabezado)
        array_shift($rows);

        // Validar el contenido del archivo
        $valid = true;
        $data = [];
        foreach ($rows as $row) {
            if (count($row) <= 7) {
                $valid = false;
                break;
            }

            $RegLote = RegistroLotes::where('Codigo', $row[0])->first();
            $subtipogasto = subtipogasto::where('codigo', $row[1])->first();

             $data[] = [
                'Lote' => $row[0],
                'RegLote' => $RegLote->id,
                'Finca' => $RegLote->finca->finca,
                'lote_name' => $RegLote->Lote->lote,
                'Codigo' => $row[1],
                'gasto_id' => $subtipogasto->tipogasto->gasto_id,
                'gasto'=> $subtipogasto->tipogasto->gasto->nombre,
                'tipogasto_id' => $subtipogasto->tipogasto_id,
                'tipo_gasto' => $subtipogasto->tipogasto->nombre,
                'subtipogasto_id' => $subtipogasto->id,
                'subtipo_gasto' => $subtipogasto->nombre,
                'Nombre' => $row[2],
                'Comprobante' => $row[3],
                'Cantidad' => doubleval($row[4]),
                'ValorUnitario' => doubleval($row[5]),
                'Total' => doubleval($row[6]),
                'Observaciones' => $row[7],
                'Error' => '',
            ];

            // Guardar en la base de datos
            consolidadogasto::create([
                'fecha' =>  DateTime::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d'),
                'reglote_id' => $RegLote->id,
                'comprobante' => $row[3],
                'cantidad' => doubleval($row[4]),
                'preciounitario' => doubleval($row[5]),
                'total' => doubleval($row[6]),
                'gasto_id' => $subtipogasto->tipogasto->gasto_id,
                'tipogasto_id' => $subtipogasto->tipogasto_id,
                'subtipogasto_id' => $subtipogasto->id,
                'observaciones' => $row[7],
            ]);
        }

        return response()->json(['success' => $valid, 'data' => $data]);
    }

    public function obtenerNombreFinca($id)
    {
        $regLote = RegistroLotes::find($id);
        if ($regLote) {
            return response()->json(['nombre' => $regLote->finca->finca]);
        }
        return response()->json(['nombre' => 'N/A'], 404);
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
    public function store(StoreconsolidadogastoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(consolidadogasto $consolidadogasto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(consolidadogasto $consolidadogasto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateconsolidadogastoRequest $request, consolidadogasto $consolidadogasto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(consolidadogasto $consolidadogasto)
    {
        //
    }
}
