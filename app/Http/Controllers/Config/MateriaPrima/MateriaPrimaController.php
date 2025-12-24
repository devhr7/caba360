<?php

namespace App\Http\Controllers\Config\MateriaPrima;

use App\Http\Controllers\Controller;
use App\Http\Requests\Config\MateriaPrima\StoreMateriaPrimaRequest;
use App\Http\Requests\Config\MateriaPrima\UpdateMateriaPrimaRequest;
use App\Models\MateriaPrima;
use App\Models\Grupo_MateriaPrima;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MateriaPrimaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data = MateriaPrima::with(['grupoMPrima:id,GrupoMateriaPrima'])
            ->select(['id', 'GrupoMPrima_id', 'MateriaPrima', 'PrecioUnit', 'slug'])
            ->get()
            ->map(function ($item) {
                $item->PrecioUnit = number_format($item->PrecioUnit, 0, ',', '.');
                return $item;
            });
        //Renderizar Vista
        return Inertia::render('Config/MateriaPrima/Explorar', [
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Config/MateriaPrima/Crear', [
            // Traer los datos del distrito para el auto completar
            'options' => [
                'OptionGrupoMP' => Grupo_MateriaPrima::optionsGrupoMateriaPrima(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMateriaPrimaRequest $request)
    {

        $MateriaPrima = new MateriaPrima;
        $MateriaPrima->GrupoMPrima_id = $request->GrupoMP['id'];
        $MateriaPrima->MateriaPrima = $request->MateriaPrima;
        $MateriaPrima->PrecioUnit = $request->PrecioUnitario;
        $MateriaPrima->save();

        return redirect(route('MateriaPrima.Explorar'));
    }

    /**
     * Display the specified resource.
     */
    public function show(MateriaPrima $materiaPrima)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $MateriaPrima = MateriaPrima::where('slug', $slug)->firstOrFail();

        return Inertia::render('Config/MateriaPrima/Edit', [
            'datos' => [
                'slug' => $MateriaPrima->slug,
                'GrupoMP' => Grupo_MateriaPrima::optionsGrupoMateriaPrima($MateriaPrima->GrupoMPrima_id),
                'MateriaPrima' => $MateriaPrima->MateriaPrima,
                'PrecioUnitario' => $MateriaPrima->PrecioUnit,
            ],
            'options' => [
                'OptionGrupoMP' => Grupo_MateriaPrima::optionsGrupoMateriaPrima(),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMateriaPrimaRequest $request, $slug)
    {
        $MateriaPrima = MateriaPrima::where('slug', $slug)->firstOrFail();

        $MateriaPrima->GrupoMPrima_id = $request->GrupoMP['id'];
        $MateriaPrima->MateriaPrima = $request->MateriaPrima;
        $MateriaPrima->PrecioUnit = $request->PrecioUnitario;
        $MateriaPrima->save();

        return redirect(route('MateriaPrima.edit', $MateriaPrima->slug));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        //
        MateriaPrima::where('slug', $slug)->firstOrFail()->delete();

        return redirect(route('MateriaPrima.Explorar'));
    }


    /**
     * Devuelve las opciones de Materia Prima en formato json
     *
     * @param int|null $grupoMPrimaId
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeOptionsMateriaPrimaScope(Request $request)
    {
        $grupoMPrimaId = $request->GrupoMPrima_id ? [$request->input('GrupoMPrima_id.id')] : null;

        return response()->json(MateriaPrima::optionsMateriaPrima($grupoMPrimaId));
    }

    public function actualizarprecio()
    {
        return Inertia::render('Config/MateriaPrima/Actualizar', [
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
        $sheet->setCellValue('D1', 'Precio unitaritario');

        $materiasPrimas = MateriaPrima::select('id','slug', 'MateriaPrima', 'PrecioUnit')->get();
        $rowNumber = 2;
        foreach ($materiasPrimas as $materiaPrima) {
            $sheet->setCellValue('A' . $rowNumber, $materiaPrima->id);
            $sheet->setCellValue('B' . $rowNumber, $materiaPrima->slug);
            $sheet->setCellValue('C' . $rowNumber, $materiaPrima->MateriaPrima);
            $sheet->setCellValue('D' . $rowNumber, $materiaPrima->PrecioUnit);
            $rowNumber++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'plantilla_materia_prima.xlsx';
        $tempFilePath = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFilePath);

        return response()->download($tempFilePath, $fileName)->deleteFileAfterSend(true);
    }

    public function validarExcel(Request $request)
    {
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        foreach ($data as $row) {
            if (!isset($row['A']) || !isset($row['C']) || !is_numeric($row['C'])) {
                return response()->json(['error' => 'Archivo no válido'], 400);
            }
        }
        return response()->json(['success' => 'Archivo válido']);
    }

    public function importarExcel(Request $request)
    {
        $file = $request->file('file');
        MateriaPrima::actualizarPreciosDesdeExcel($file->getPathname());
        return redirect()->route('MateriaPrima.Explorar');
    }
}
