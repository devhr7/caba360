<?php

namespace App\Http\Controllers\Modulos\Costos\Ingresos;

use App\Http\Controllers\Controller;
use App\Models\consolidadoingreso;
use App\Http\Requests\StoreconsolidadoingresoRequest;
use App\Http\Requests\UpdateconsolidadoingresoRequest;
use App\Models\RegistroLotes;
use DateTime;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;


class ConsolidadoingresoController extends Controller
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
        return Inertia::render('Modulos/Costos/Ingresos/Importar/index', []);
    }


    public function importar_movimientos_validacion(Request $request) //vista
    {
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Omitir la primera fila (encabezado)
        array_shift($rows);

        $valid = true;
        $data = [];
        foreach ($rows as $row) {
            if (count($row) != 5) {
                $valid = false;
                break;
            }

            $RegLote = RegistroLotes::where('Codigo', $row[0])->first();

            if (!$RegLote) {
                $valid = false;
                $data[] = [
                    'Lote' => $row[0],
                    'Documento' => $row[1],
                    'Kilogramos' => $row[2],
                    'ValorVenta' => $row[3],
                    'Observaciones' => $row[4],
                    'Error' => 'El lote no se encuentra en la base de datos',
                ];
                continue;
            }

            if (!is_numeric($row[2]) || !is_numeric($row[3])) {
                $valid = false;
                $data[] = [
                    'Lote' => $row[0],
                    'Documento' => $row[1],
                    'Kilogramos' => $row[2],
                    'ValorVenta' => $row[3],
                    'Observaciones' => $row[4],
                    'Error' => 'Kilogramos y Valor Venta deben ser valores numéricos',
                ];
                continue;
            }

            $data[] = [
                'fecha' => DateTime::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d'),
                'Lote' => $row[0],
                'RegLote' => $RegLote->id,
                'Documento' => $row[1],
                'Kilogramos' => doubleval($row[2]),
                'ValorVenta' => doubleval($row[3]),
                'Observaciones' => $row[4],
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

        $valid = true;
        $data = [];
        foreach ($rows as $row) {
            if (count($row) != 5) {
                $valid = false;
                break;
            }

            $RegLote = RegistroLotes::where('Codigo', $row[0])->first();

            $data[] = [
                'Lote' => $row[0],
                'RegLote' => $RegLote->id,
                'Documento' => $row[1],
                'Kilogramos' => doubleval($row[2]),
                'ValorVenta' => doubleval($row[3]),
                'Observaciones' => $row[4],
                'Error' => '',
            ];

            // Guardar en la base de datos
            consolidadoingreso::create([
                'fecha' => DateTime::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d'),
                'reglote_id' => $RegLote->id,
                'documento' => $row[1],
                'kilogramos' => doubleval($row[2]),
                'totalventa' => doubleval($row[3]),
                'observaciones' => $row[4],
            ]);
        }

        return response()->json(['success' => $valid, 'data' => $data]);
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
    public function store(StoreconsolidadoingresoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(consolidadoingreso $consolidadoingreso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(consolidadoingreso $consolidadoingreso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateconsolidadoingresoRequest $request, consolidadoingreso $consolidadoingreso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(consolidadoingreso $consolidadoingreso)
    {
        //
    }
}
