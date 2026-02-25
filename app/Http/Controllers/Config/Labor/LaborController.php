<?php

namespace App\Http\Controllers\Config\Labor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Config\Labor\StoreLaborRequest;
use App\Http\Requests\Config\Labor\UpdateLaborRequest;
use App\Models\Labor;
use App\Models\TipoCumplido;
use App\Models\TipoLabor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

class LaborController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labor = Labor::all()->map(function ($data) {
            return [
                'id' => $data->id,
                'labor' => $data->labor,
                'TipoCumplido' => $data->TipoCumplido_id ? $data->TipoCumplido->TipoCumplido : null,
                'CostoHect' => $data->CostoHect,
                'slug' => $data->slug,
            ];
        });

        //Renderizar Vista
        return Inertia::render('Config/Labor/index', [
            'labor' => $labor,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Config/Labor/Crear', [
            // Traer los datos del distrito para el auto completar
            'options' => [
                'OptionTipoLabor' => TipoLabor::optionsTipoLabor(),
                'OptionTipoCumplido' => TipoCumplido::optionsTipoCumplido(),
            ]
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLaborRequest $request)
    {
        //
        // Create a new Labor instance
        $labor = new Labor();

        // Assign values from the request to the Labor model
        $labor->labor = $request->labor;
        $labor->CostoHect = $request->costoHect;
        $labor->TipoLabor_id = $request->TipoLabor['id'];
        $labor->TipoCumplido_id = $request->TipoCumplido['id'];
        $labor->CumpMaqV = $request->CumplidoMaquinaria;
        $labor->CumpApliV = $request->CumplidoAplicacion;
        $labor->CumpOrdV = $request->CumplidoOrdenServicio;
        $labor->Hect = $request->boolean('Hect');
        //$labor->CumpLabV = $request->CumplidoLaboresCampo;

        // Save the Labor model to the database
        $labor->save();

        // Redirect to a specific route with a success message
        return redirect()->route('Labor.Explorar')->with('success', 'Labor creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Labor $labor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $labor = labor::where('slug', $slug)->firstOrFail();
        //Renderizar Vista
        return Inertia::render('Config/Labor/Edit', [
            'datos' => [
                'id' => $labor->id,
                'labor' => $labor->labor,
                'costoHect' => $labor->CostoHect,
                'TipoLabor' =>  TipoLabor::optionsTipoLabor($labor->TipoLabor_id),
                'TipoCumplido' => TipoCumplido::optionsTipoCumplido($labor->TipoCumplido_id),
                'CumplidoMaquinaria' => $labor->CumpMaqV == 1 ? true : false,
                'CumplidoAplicacion' => $labor->CumpApliV == 1 ? true : false,
                'CumplidoOrdenServicio' => $labor->CumpOrdV == 1 ? true : false,
                'CumplidoLaboresCampo' => false,
                'Hect' => $labor->Hect == 1 ? true : false,


                'slug' => $labor->slug,
                'update_url' => route('Labor.update', $labor->slug),
                'delete_url' => route('Labor.delete', $labor->slug),
            ],
            // Traer los datos del distrito para el auto completar
            'options' => [
                'OptionTipoLabor' => TipoLabor::optionsTipoLabor(),
                'OptionTipoCumplido' => TipoCumplido::optionsTipoCumplido(),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLaborRequest $request, $slug)
    {
        // Find the Labor instance by slug
        $labor = Labor::where('slug', $slug)->firstOrFail();

        // Update values from the request to the Labor model
        $labor->labor = $request->labor;
        $labor->CostoHect = $request->costoHect;
        $labor->TipoLabor_id = $request->TipoLabor['id'];
        $labor->TipoCumplido_id = $request->TipoCumplido['id'];
        $labor->CumpMaqV = $request->CumplidoMaquinaria;
        $labor->CumpApliV = $request->CumplidoAplicacion;
        $labor->CumpOrdV = $request->CumplidoOrdenServicio;
        $labor->Hect = $request->boolean('Hect');

        // Save the updated Labor model to the database
        $labor->save();

        // Redirect to a specific route with a success message
        return redirect()->route('Labor.edit', $labor->slug)->with('success', 'Labor actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        // Find the Labor instance by slug
        $labor = Labor::where('slug', $slug)->firstOrFail();

        // Delete the Labor instance
        $labor->delete();

        // Redirect to a specific route with a success message
        return redirect()->route('Labor.Explorar')->with('success', 'Labor eliminada exitosamente.');
    }

    public function getOptionsLabor(Request $request)
    {
        $data = Labor::select(['id', 'labor', 'CostoHect', 'slug'])->get()->map(function ($data) {
            return [
                'id' => $data->id,
                'label' => $data->labor . " | " . $data->CostoHect,
            ];
        });
        return response()->json($data);
    }


    public function actualizar()
    {
        // Actualiza los precios masivamente
        return Inertia::render('Config/Labor/Actualizar', [
            // Traer los datos del distrito para el auto completar

        ]);
    }

    public function descargarPlantilla()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Codigo');
        $sheet->setCellValue('C1', 'nombre');
        $sheet->setCellValue('D1', 'CostoHect');
        $sheet->setCellValue('E1', 'Cumplido');

        $labores = Labor::with('TipoCumplido')->select('id', 'slug', 'labor', 'TipoCumplido_id', 'CostoHect')->get();
        $rowNumber = 2;
        foreach ($labores as $labor) {
            $sheet->setCellValue('A' . $rowNumber, $labor->id);
            $sheet->setCellValue('B' . $rowNumber, $labor->slug);
            $sheet->setCellValue('C' . $rowNumber, $labor->labor);
            $sheet->setCellValue('D' . $rowNumber, $labor->CostoHect);
            $sheet->setCellValue('E' . $rowNumber, $labor->TipoCumplido_id ? $labor->TipoCumplido->TipoCumplido : null);
            $rowNumber++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'plantilla_labor.xlsx';
        $tempFilePath = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFilePath);

        return response()->download($tempFilePath, $fileName)->deleteFileAfterSend(true);
    }

    public function validarExcel(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No se ha subido ningún archivo'], 400);
        }

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $formattedData = [];
        $isFirstRow = true;
        foreach ($data as $row) {
            if ($isFirstRow) {
                $isFirstRow = false;
                continue;
            }
            if (!isset($row['A']) || !isset($row['C']) || !isset($row['D'])) {
                return response()->json(['error' => 'Archivo no válido'], 400);
            }
            $formattedData[] = [
                'id' => $row['A'],
                'slug' => $row['B'],
                'labor' => $row['C'],
                'CostoHect' => $row['D'],
                'TipoCumplido' => $row['E'] ?? null,
            ];
        }

        return response()->json(['data' => $formattedData]);
    }

    public function importarExcel(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No se ha subido ningún archivo'], 400);
        }

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        foreach ($data as $row) {
            if (empty($row['A'])) {
                // Si no hay ID en la columna A, verifica si el registro no existe en la base de datos
                if (!Labor::where('labor', $row['C'])->exists()) {
                    // Crear un nuevo registro de Labor
                    Labor::create([
                        'slug' =>  Str::slug($row['B']),
                        'labor' => $row['C'],
                        'CostoHect' => $row['D'],
                        'TipoCumplido_id' => TipoCumplido::where('TipoCumplido', $row['E'])->first()->id ?? null,
                    ]);
                }
            } elseif (is_numeric($row['A'])) {
                // Si hay un ID en la columna A, verifica si el registro existe en la base de datos
                $labor = Labor::find($row['A']);
                if ($labor) {
                    // Actualizar el registro existente de Labor
                    $labor->slug = Str::slug($row['B']);
                    $labor->labor = $row['C'];
                    $labor->CostoHect = $row['D'];
                    $labor->TipoCumplido_id = TipoCumplido::where('TipoCumplido', $row['E'])->first()->id ?? null;
                    $labor->save();
                }
            }
        }

        return redirect()->route('Labor.Explorar');
    }
}
