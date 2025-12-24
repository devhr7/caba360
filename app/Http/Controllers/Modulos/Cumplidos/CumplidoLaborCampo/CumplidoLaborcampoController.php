<?php

namespace App\Http\Controllers\Modulos\Cumplidos\CumplidoLaborCampo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modulos\Cumplidos\CumplidoLaborCampo\Storecumplido_laborcampoRequest;
use App\Http\Requests\Modulos\Cumplidos\CumplidoLaborCampo\Updatecumplido_laborcampoRequest;
use App\Http\Resources\Modulos\Cumplido\CumplidoLaborCampo\CumplidoLaborCampoCollection;

use App\Models\cumplido_laborcampo;

use App\Models\cumplido_laborcampodetallecuadrilla;
use App\Models\cumplido_laborcampodetallelote;
use App\Models\CumplidoLaboresCampo;
use App\Models\Empleados;
use App\Models\Finca;
use App\Models\Labor;
use App\Models\Lote;
use App\Models\UnidadMedida;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Ramsey\Uuid\Type\Integer;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; // Exportar Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet; // Carga La Libreria Excel
use Illuminate\Http\Request;

class CumplidoLaborcampoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = cumplido_laborcampo::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'codigo' => $item->codigo,
                'fecha' => $item->fecha,
                'finca' => $item->finca_id ? $item->Finca->finca : '',

                'lote' => cumplido_laborcampodetallelote::with('RegLote')->where('cump_laborcampo_id', $item->id)->get()->map(function ($reg) {
                    return $reg->reg_lote_id ? $reg->RegLote->Lote->lote : null;
                })->filter()->implode(', '),
                'cuadrilla' => cumplido_laborcampodetallecuadrilla::with('Personal')->where('cump_laborcampo_id', $item->id)->get()->map(function ($reg) {
                    return $reg->personal_id ? $reg->Personal->nombre1 : null;
                })->filter()->implode(', '),

                'labor' => $item->labor_id ?  $item->Labor->labor : null,
                'cantidad' => $item->cantidadtotal,
                'UnidadMedida' => $item->unidadmedida_id ? $item->Unidadmedida->UnidadMedida : null,

                'fecha_cierre' => $item->fecha_cierre ? $item->fecha_cierre : false,
                'fecha_cierre_f' => $item->fecha_cierre ? Carbon::parse($item->fecha_cierre)->format('d/m/Y') : false,
                'verificado' => $item->fecha_cierre ? true : false,

            ];
        });

        return Inertia::render('Modulos/Cumplidos/CumplidoLaborCampo/Index', [
            'data' => $data,
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        if (! Gate::allows('mod.cump_aplicacion.create')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        return Inertia::render('Modulos/Cumplidos/CumplidoLaborCampo/Crear', [
            'options' => [
                'get_finca' => Finca::optionsFinca(),
                'get_labor' => Labor::optionsLabor(null, 1),
                'get_unidadmedida' => UnidadMedida::optionsUnidadMedida(),
                //'get_personal' => Empleados::optionsEmpleado(null,),
            ],

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Storecumplido_laborcampoRequest $request)
    {
        //
        $cumplido_laborcampo = new cumplido_laborcampo();
        $cumplido_laborcampo->codigo =  $request->codigo;
        $cumplido_laborcampo->fecha = Carbon::parse($request->fecha)->format('Y-m-d');
        $cumplido_laborcampo->finca_id = $request->finca['id'];
        $cumplido_laborcampo->labor_id = $request->labor['id'];
        $cumplido_laborcampo->unidadmedida_id = $request->UnidadMedida['id'];
        $cumplido_laborcampo->cantidadtotal = doubleval($request->CantidadTotal);
        $cumplido_laborcampo->save();
        return redirect()->route('CumpLaborCampo.edit', $cumplido_laborcampo->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(cumplido_laborcampo $cumplido_laborcampo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $CumpLaborCampo)
    {

        $cumplidoLaborCampo = cumplido_laborcampo::with(['Unidadmedida'])->where('id', $CumpLaborCampo)->first();

        $data = [
            'id' => $cumplidoLaborCampo->id,
            'codigo' => $cumplidoLaborCampo->codigo,
            'fecha' => $cumplidoLaborCampo->fecha,
            'finca' => $cumplidoLaborCampo->finca_id ? Finca::optionsFinca(null, $cumplidoLaborCampo->finca_id) : Finca::optionsFinca(),
            'labor' => $cumplidoLaborCampo->labor_id ? Labor::optionsLabor(null, null, $cumplidoLaborCampo->labor_id) : Labor::optionsLabor(null, 1),
            'unidadmedida' => $cumplidoLaborCampo->unidadmedida_id ? UnidadMedida::optionsUnidadMedida($cumplidoLaborCampo->unidadmedida_id) : UnidadMedida::optionsUnidadMedida(),
            'cantidadtotal' => $cumplidoLaborCampo->cantidadtotal,
        ];

        return Inertia::render('Modulos/Cumplidos/CumplidoLaborCampo/Edit', [
            'data' => $data,
            'datatable' => [
                'datatable_lotes' => cumplido_laborcampodetallelote::with('RegLote')->where('cump_laborcampo_id', $cumplidoLaborCampo->id)->get()->map(function ($reg) {
                    return [
                        'id' => $reg->id,
                        'lote' => $reg->reg_lote_id ? $reg->RegLote->Lote->lote : null,
                        'codigo_lote' => $reg->reg_lote_id ? $reg->RegLote->Codigo : null,
                        'Hect' => $reg->reg_lote_id ? $reg->RegLote->Hect : null,
                        'cantidad' => number_format($reg->cantidad, 2, ",", "."),
                        'cantidad_sinf' => $reg->cantidad,
                        'total' => number_format($reg->cantidad, 2, ",", "."),
                        'total_sinf' => $reg->cantidad,

                    ];
                }),
                'datatable_cuadrilla' => cumplido_laborcampodetallecuadrilla::with(['Personal'])->where('cump_laborcampo_id', $cumplidoLaborCampo->id)->get()->map(function ($reg) {
                    return [
                        'id' => $reg->id,
                        'identificacion' => $reg->personal_id ? $reg->Personal->identificacion : null,
                        'empleado' => $reg->personal_id ? $reg->Personal->nombre1 : null,
                        'cantidad' => number_format($reg->cantidad, 2, ",", "."),
                        'cantidad_sinf' => $reg->cantidad,
                        'valor_unit' => number_format($reg->costo_unit, 2, ",", "."),
                        'total_sinf' => $reg->total_bonificacion,
                        'total' => number_format($reg->total_bonificacion, 2, ",", "."),

                    ];
                }),
            ],
            'options' => [
                'get_finca' => Finca::optionsFinca(),
                'get_lote' => Lote::optionsLote($cumplidoLaborCampo->finca_id),
                'get_labor' => Labor::optionsLabor(null, 1),
                'get_unidadmedida' => UnidadMedida::optionsUnidadMedida(),
                'get_empleados' => Empleados::optionsEmpleado(),
                //'get_personal' => Empleados::optionsEmpleado(null,),
            ],

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatecumplido_laborcampoRequest $request, $CumpLaborCampo)
    {
        $cumplido_laborcampo = cumplido_laborcampo::find($CumpLaborCampo);
        $cumplido_laborcampo->codigo =  $request->codigo;
        $cumplido_laborcampo->fecha = Carbon::parse($request->fecha)->format('Y-m-d');
        $cumplido_laborcampo->finca_id = $request->finca['id'];
        $cumplido_laborcampo->labor_id = $request->labor['id'];
        $cumplido_laborcampo->unidadmedida_id = $request->UnidadMedida['id'];
        $cumplido_laborcampo->cantidadtotal = doubleval($request->CantidadTotal);
        $cumplido_laborcampo->save();

        $CumplidoLaborcampodetallecuadrillaController = new CumplidoLaborcampodetallecuadrillaController();
        $CumplidoLaborcampodetalleloteController = new CumplidoLaborcampodetalleloteController();


        $CumplidoLaborcampodetallecuadrillaController->ActualizarCantidad($cumplido_laborcampo->id);
        $CumplidoLaborcampodetalleloteController->ActualizarCantidad($cumplido_laborcampo->id);



        return redirect()->route('CumpLaborCampo.edit', $cumplido_laborcampo->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cumplido_laborcampo)
    {

        $cumplido_laborcampo = cumplido_laborcampo::find($cumplido_laborcampo);

        cumplido_laborcampodetallecuadrilla::where('cump_laborcampo_id', $cumplido_laborcampo->id)->delete();
        cumplido_laborcampodetallelote::where('cump_laborcampo_id', $cumplido_laborcampo->id)->delete();

        $cumplido_laborcampo->delete();

        return redirect()->route('CumpLaborCampo.Explorar');
    }

    public function DataReport(Request $request)
    {
        $fecha_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');

        $cumplidoLaborCampo = cumplido_laborcampodetallecuadrilla::with(['CumpLaborcampo'])->whereHas('CumpLaborcampo', function ($query) use ($fecha_inicio, $fecha_fin) {
            $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
        })
            ->get()
            ->map(function ($reg) {
                return [
                    'cumplido' => $reg->CumpLaborcampo->codigo,
                    'fecha' => Carbon::parse($reg->CumpLaborcampo->fecha)->format('d/m/Y'),
                    'distrito' => is_null($reg->CumpLaborcampo->finca_id) ? null : $reg->CumpLaborcampo->Finca->Distrito->distrito,
                    'CentroCosto' => is_null($reg->CumpLaborcampo->finca_id) ? null : $reg->CumpLaborcampo->Finca->zona->CentroCosto,
                    'zona' => is_null($reg->CumpLaborcampo->finca_id) ? null : $reg->CumpLaborcampo->Finca->zona->zona,
                    'finca' =>  !is_null($reg->CumpLaborcampo->finca_id) ? $reg->CumpLaborcampo->Finca->finca : null,
                    'lote' => cumplido_laborcampodetallelote::with('RegLote')->where('cump_laborcampo_id', $reg->CumpLaborcampo->id)->get()->map(function ($reg) {
                        return $reg->reg_lote_id ? $reg->RegLote->Lote->lote : null;
                    })->filter()->implode(', '),
                    'identificacion' => $reg->personal_id ? $reg->Personal->identificacion : null,
                    'nombre' => $reg->personal_id ? $reg->Personal->nombre1 : null,
                    'labor' => $reg->CumpLaborcampo->labor_id ? $reg->CumpLaborcampo->Labor->labor : null,
                    'cantidad' => $reg->cantidad,
                    'costo_unit' => $reg->costo_unit,
                    'total_bonificacion' => $reg->total_bonificacion,
                    'CantidadTotal' => $reg->CumpLaborcampo->cantidadtotal,
                'fecha_cierre' => $reg->CumpLaborcampo->fecha_cierre ? Carbon::parse($reg->CumpLaborcampo->fecha_cierre)->format('d/m/Y') : null,

                ];
            });


        return $cumplidoLaborCampo;
    }




    public function Exportarxlsx(Request $request)
    {
        $CumplidoAplicacion =  collect($this->DataReport($request));
        // Crear un nuevo objeto Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados de las columnas
        $sheet->setCellValue('A1', 'Cumplido');
        $sheet->setCellValue('B1', 'Fecha Cumplido');
        $sheet->setCellValue('C1', 'Distrito');
        $sheet->setCellValue('D1', 'Centro Costo');
        $sheet->setCellValue('E1', 'Zona');
        $sheet->setCellValue('F1', 'Finca');
        $sheet->setCellValue('G1', 'Lote');


        $sheet->setCellValue('H1', 'Identificacion Equipo');
        $sheet->setCellValue('I1', 'Nombre Equipo');
        $sheet->setCellValue('J1', 'Labor');
        $sheet->setCellValue('K1', 'Cantidad');
        $sheet->setCellValue('L1', 'Valor Unit');
        $sheet->setCellValue('M1', 'Total Bonificacion');
        $sheet->setCellValue('N1', 'Cantidad Total');
        $sheet->setCellValue('O1', 'Fecha Cierre');


        $styleFila1 = [
            'font' => [
                'bold' => true,
                'color' => [
                    'argb' => 'FFFFFF',
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '0D3161',
                ],
                'endColor' => [
                    'argb' => '0D3161',
                ],
            ]
        ];

        $styleFilas = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ]
            ],
        ];

        $spreadsheet->getActiveSheet()->getStyle('A1:O1')->applyFromArray($styleFila1);


        // Escribir los datos en el archivo Excel
        $row = 2;
        foreach ($CumplidoAplicacion as $cumplido) {
            $sheet->setCellValue('A' . $row, $cumplido['cumplido']);
            $sheet->setCellValue('B' . $row, $cumplido['fecha']);
            $sheet->setCellValue('C' . $row, $cumplido['distrito']);
            $sheet->setCellValue('D' . $row, $cumplido['CentroCosto']);
            $sheet->setCellValue('E' . $row, $cumplido['zona']);
            $sheet->setCellValue('F' . $row, $cumplido['finca']);
            $sheet->setCellValue('G' . $row, $cumplido['lote']);
            $sheet->setCellValue('H' . $row, $cumplido['identificacion']);
            $sheet->setCellValue('I' . $row, $cumplido['nombre']);
            $sheet->setCellValue('J' . $row, $cumplido['labor']);
            $sheet->setCellValue('K' . $row, $cumplido['cantidad']);
            $sheet->setCellValue('L' . $row, $cumplido['costo_unit']);
            $sheet->setCellValue('M' . $row, $cumplido['total_bonificacion']);
            $sheet->setCellValue('N' . $row, $cumplido['CantidadTotal']);
            $sheet->setCellValue('O' . $row, $cumplido['fecha_cierre']);


            $row++;
        }

        $spreadsheet->getActiveSheet()->getStyle('A2:O' . $row)->applyFromArray($styleFilas);


        // Configurar la respuesta para descargar el archivo
        $fileName = 'cumplidos_labor_campo.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/' . $fileName));

        // Descargar el archivo Excel
        return response()->download(storage_path('app/public/' . $fileName), $fileName, [
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ])->deleteFileAfterSend(true);
    }

    public function verificar(Request $request)
    {

        if (! Gate::allows('mod.cump_aplicacion.edit.verificar')) {
            abort(403, 'No tienes permiso para acceder.');
        }


        if (isset($request->cumplido) && !empty($request->cumplido)) {
            if (!isset($request['fechaCierre']) || empty($request['fechaCierre'])) {
                $cumplidoLaborCampo = cumplido_laborcampo::where('id', $request->cumplido)->update([
                    'fecha_cierre' => null,  // Asignar el número de factura a este "cumplido"
                    'verificado' => false,
                ]);


            } else {
                $cumplidoLaborCampo = cumplido_laborcampo::where('id', $request->cumplido)->update([
                    'fecha_cierre' => Carbon::parse($request->fechaCierre)->format('Y-m-d'),  // Asignar el número de factura a este "cumplido"
                    'verificado' => true,
                ]);

            }
        } elseif (isset($request->selectedCumplidos) && !empty($request->selectedCumplidos)) {
            foreach ($request->selectedCumplidos as $row) {
                if (!isset($request['fechaCierre']) || empty($request['fechaCierre'])) {
                    $cumplidoLaborCampo = cumplido_laborcampo::where('id', $row['id'])->update([
                        'fecha_cierre' => null,  // Asignar el número de factura a este "cumplido"
                        'verificado' => false,
                    ]);

                } else {
                    $cumplidoLaborCampo = cumplido_laborcampo::where('id', $row['id'])->update([
                        'fecha_cierre' => Carbon::parse($request->fechaCierre)->format('Y-m-d'),  // Asignar el número de factura a este "cumplido"
                        'verificado' => true,
                    ]);

                }
            }
        }

        //$CumplidoMaquinaria->save();
        return redirect(route('CumpLaborCampo.Explorar'))->with('success', 'Cumplido Labor Campo Verificado correctamente');
    }


    public function report()
    {
        // Verificar permisos manualmente en el controlador


        return inertia::render('Modulos/Cumplidos/CumplidoLaborCampo/Exportar', []);
    }


 public function getCumplidosLaborCampo()
    {


        $cumplidoLaborCampo = cumplido_laborcampodetallecuadrilla::with(['CumpLaborcampo'])->get();

        return new CumplidoLaborCampoCollection($cumplidoLaborCampo);
    }
}
