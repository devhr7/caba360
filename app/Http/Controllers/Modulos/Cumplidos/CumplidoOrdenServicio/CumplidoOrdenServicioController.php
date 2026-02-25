<?php

namespace App\Http\Controllers\Modulos\Cumplidos\CumplidoOrdenServicio;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modulos\Cumplidos\CumplidoOrdenServicio\StoreCumplidoOrdenServicioRequest;
use App\Http\Requests\Modulos\Cumplidos\CumplidoOrdenServicio\UpdateCumplidoOrdenServicioRequest;
use App\Models\ClasificacionCentroCosto;
use App\Models\contratista;
use App\Models\CumplidoOrdenServicio;
use App\Models\CumplidoOrdenServicioDetalle;
use App\Models\Empleados;
use App\Models\Finca;
use App\Models\Labor;
use App\Models\Lote;
use App\Models\Potrero;
use App\Models\RecordVisita;
use App\Models\TipoCultivo;
use App\Models\UnidadMedida;
use Illuminate\Support\Facades\Gate; // Libreria para verificacion de permisos
use Inertia\Inertia;
use Illuminate\Support\Str;
use Carbon\Carbon;


use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet; // Carga La Libreria Excel
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; // Exportar Excel

class CumplidoOrdenServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verificar permisos manualmente en el controlador
        // Si no tiene permiso para explorar, se aborta con un status 403


        if (! Gate::allows('mod.cump_ordenservicio.show')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }


        // Consulta
        // Se consulta la tabla de cumplidos de maquinaria y se traen los datos de las relaciones
        // Se seleccionan los campos que se van a mostrar en la vista
        // Se utilizan los modelos para traer los nombres de los campos en lugar de los ids
        // Se utiliza map para transformar los datos en un formato que se pueda mostrar en la vista
        $CumplidoOrdenServicio = CumplidoOrdenServicio::orderBy('fecha', 'desc')->get()->map(function ($data) {
            return [
            'id' => $data->id,
            'slug' => $data->slug,
            'codigo' => $data->codigo,
            'fecha' => $data->fecha,
            'contratista' => $data->contratista->nombre,
            'autorizado' => $data->Empleados->nombre1,
            'total' => $data->CumplidoOrdenServicioDetalle->sum('Total'),
            'Format_Total' => number_format($data->CumplidoOrdenServicioDetalle->sum('Total'), 2, ',', '.'),
            'verificado' => is_null($data->factura) ? false : true,
            'factura' => $data->factura,
            'detalle' => CumplidoOrdenServicioDetalle::where('CumplidoOrdenServicio_id', $data->id)->get()->map(function ($reg){
               return [
                'finca' => $reg->finca_id ? $reg->Finca->finca : null,
                'lote' => ($reg->Lote_id ?  $reg->Lote->lote : null )." | ".  $reg->DestinoServicio,
                'labor' =>($reg->Labor_id ? $reg->Labor->labor : null) .  " | " .  $reg->DetalleLabor,
                'infrecord' => $record = RecordVisita::where('Codigo', $reg->RecordVisita)->first(),
                'record' => [
                    'Codigo' => $record ? $record->Codigo : null,
                    'fecha' => $record ? Carbon::parse($record->fecha)->format('d/m/Y') : null,
                    'finca' => $record ? $record->finca->finca : null,
                    'lote' => $record ? $record->lote->lote : null,
                    'fincalote' => $record ? $record->finca->finca . ' | ' . $record->lote->lote : null,
                    'diagnostico' => $record ? $record->diagnostico : null,
                    'observaciones' => $record ? $record->observaciones : null,
                ]

               ];
            }),

            ];
        });

        //Renderizar Vista
        // Se renderiza la vista con los datos de los cumplidos de maquinaria y las rutas para crear y editar
        return Inertia::render('Modulos/Cumplidos/CumplidoOrdenServicio/Explorar', [
            'CumplidoOrdenServicio' => $CumplidoOrdenServicio,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar permisos manualmente en el controlador
        if (! Gate::allows('mod.cump_ordenservicio.create')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        // Renderizar Vista
        return Inertia::render('Modulos/Cumplidos/CumplidoOrdenServicio/Crear', [
            'options' => [
                'optionsContratista' => contratista::optionsContratista(),
                'optiosEmpleado' => Empleados::optionsEmpleado(null, [4, 5, 6]),
            ],

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCumplidoOrdenServicioRequest $request)
    {
        //
        $CumplidoOrdenServicio = CumplidoOrdenServicio::create([
            'codigo' => $request->codigo,
            'fecha' => empty($request->fecha) ? null :  Carbon::parse($request->fecha)->format('Y-m-d'),
            'Responsable_id' => $request->contratista['id'],
            'Autorizacion_id' => $request->autorizado['id'],
        ]);

        return redirect(route('CumplidoOrdenServicio.edit', $CumplidoOrdenServicio->slug))->with('success', 'Cumplido creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(CumplidoOrdenServicio $cumplidoOrdenServicio)
    {
        //


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        //
        $CumplidoOrdenServicio = CumplidoOrdenServicio::where('slug', $slug)->firstOrFail();

        return Inertia::render(
            'Modulos/Cumplidos/CumplidoOrdenServicio/Edit',
            [
                'datos' => [
                    'id' => $CumplidoOrdenServicio->id,
                    'slug' => $CumplidoOrdenServicio->slug,
                    'codigo' => $CumplidoOrdenServicio->codigo,
                    'fecha' => $CumplidoOrdenServicio->fecha,
                    'contratista' => contratista::optionsContratista($CumplidoOrdenServicio->Responsable_id),
                    'autorizado' => Empleados::optionsEmpleado($CumplidoOrdenServicio->Autorizacion_id),
                ],
                'datatable' => $CumplidoOrdenServicio->CumplidoOrdenServicioDetalle->map(function ($data) {
                    return [
                        'id' => $data->id,
                        'RegLote_id' => is_null($data->RegLote_id) ? "" : $data->RegLote_id,
                        'RegLote_Codigo' => is_null($data->RegLote_id) ? "" : $data->RegLote_id,
                        'RegLote_Hect' => is_null($data->RegLote_id) ? "" : $data->RegLote_id,
                        'OptionsPotrero' => is_null($data->potrero_id) ? "" : Potrero::optionsPotrero($data->Finca->id, $data->potrero_id),
                        'lote' =>  is_null($data->Lote_id) ?  optional($data->Potrero)->nombre: optional($data->Lote)->lote,
                        'Optionslote' => is_null($data->Lote_id) ? "" : lote::optionsLote(null, $data->Lote_id),
                        'finca' => is_null($data->finca_id) ? "" : $data->Finca->finca,
                        'Optionsfinca' => is_null($data->finca_id) ? "" : Finca::optionsFinca(null, $data->finca_id),
                        'destino' => $data->DestinoServicio,
                        'labor' => is_null($data->Labor_id) ? "" : $data->Labor->labor,
                        'Optionslabor' => is_null($data->Labor_id) ? "" : Labor::optionsLabor(null, null, $data->Labor_id),
                        'DetalleLabor' => $data->DetalleLabor,
                        'unidad_medida' => $data->UnidadMedida->UnidadMedida,
                        'Options_unidad_medida' => is_null($data->UnidadMedida_id) ? "" : UnidadMedida::optionsUnidadMedida($data->UnidadMedida_id),
                        'tipo_centro_costo' => $data->ClasificacionCentroCosto->ClaseCentroCosto,
                        'Options_tipo_centro_costo' => is_null($data->TipoCentroCosto_id) ? "" : ClasificacionCentroCosto::optionsClaseCentroCosto($data->TipoCentroCosto_id),
                        'cantidad' => $data->Cantidad,
                        'PrecioUnitario' => $data->ValorUnit,
                        'FormatPrecioUnitario' => number_format($data->ValorUnit, 2, ',', '.'),
                        'Total' => $data->Total,
                        'FormatTotal' => number_format($data->Total, 2, ',', '.'),
                        'record' => $data->RecordVisita_id == null ? $data->RecordVisita : RecordVisita::find($data->RecordVisita_id)->only(['id', 'Codigo']),


                        'Observaciones' => $data->Observaciones,
                    ];
                }),
                'options' => [
                    'optionsContratista' => contratista::optionsContratista(),
                    'optionsEmpleado' => Empleados::optionsEmpleado(null, [4, 5, 6]),
                    'optionsFinca' => Finca::optionsFinca(),
                    'optionsLote' => Lote::optionsLote(),
                    'optionsLabor' => Labor::optionsLabor(null, 3),
                    'optionsUnidadMedida' => UnidadMedida::optionsUnidadMedida(),
                    'optionsTipoCentroCosto' => ClasificacionCentroCosto::optionsClaseCentroCosto(null, [1, 2, 5]),
                    'optionsPotrero' => Potrero::optionsPotrero(),
                    'getRecord' =>   RecordVisita::all()->map(function ($reg) {
                        return [
                            "id" => $reg->id,
                            "Codigo" => $reg->Codigo
                        ];
                    }),
                ],
            ],
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCumplidoOrdenServicioRequest $request, $cumplidoOrdenServicio)
    {
        //
        $cumplidoOrdenServicio = CumplidoOrdenServicio::where('slug', $cumplidoOrdenServicio)->firstOrFail();


        $cumplidoOrdenServicio->codigo = $request->codigo;
        $cumplidoOrdenServicio->fecha = empty($request->fecha) ? null :  Carbon::parse($request->fecha)->format('Y-m-d');
        $cumplidoOrdenServicio->Responsable_id = $request->contratista['id'];
        $cumplidoOrdenServicio->Autorizacion_id = $request->autorizado['id'];

        $cumplidoOrdenServicio->save();

        //return to_route('CumplidoAplicacion.edit', $CumplidoAplicacion);
        return redirect()->route('CumplidoOrdenServicio.edit', $cumplidoOrdenServicio->slug)->with('success', 'Cumplido Actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cumplidoOrdenServicio)
    {
        //
        $cumplidoOrdenServicio = CumplidoOrdenServicio::where('slug', $cumplidoOrdenServicio)->firstOrFail();
        $cumplidoOrdenServicioDetalle = CumplidoOrdenServicioDetalle::where('CumplidoOrdenServicio_id', $cumplidoOrdenServicio->id)->delete();
        $cumplidoOrdenServicio->delete();

        return redirect(route('CumplidoOrdenServicio.Explorar'));
    }

    /** Reporte */

    public function report()
    {
        // Verificar permisos manualmente en el controlador
        return inertia::render('Modulos/Cumplidos/CumplidoOrdenServicio/Report', []);
    }
    public function DataReport(Request $request)
    {
        $fecha_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');

        $cumplidoOrdenServicio = CumplidoOrdenServicioDetalle::with(['CumplidoOrdenServicio'])->whereHas('CumplidoOrdenServicio', function ($query) use ($fecha_inicio, $fecha_fin) {
            $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
        })
            ->get()->map(function ($data) {
                return [
                    'cumplido' => $data->CumplidoOrdenServicio->codigo,
                    'fecha' => Carbon::parse($data->CumplidoOrdenServicio->fecha)->format('d/m/Y'),
                    'contratista_identificacion' => $data->CumplidoOrdenServicio->contratista->identificacion,
                    'contratista_nombre' => $data->CumplidoOrdenServicio->contratista->nombre,
                    'TipoCentroCosto' => $data->ClasificacionCentroCosto->ClaseCentroCosto,
                    'Distrito' => is_null($data->finca_id) ? "" :  $data->Finca->Distrito->distrito,
                    'Zona' => is_null($data->finca_id) ? "" : (is_null($data->Finca->zona_id) ? "" : $data->Finca->zona->zona),
                    'Finca' => is_null($data->finca_id) ? "" :  $data->Finca->finca,
                    'Lote' => is_null($data->Lote_id) ? "" : $data->Lote->lote,
                    'CodigoLote' => is_null($data->RegLote_id) ? "" :  $data->RegLote->Codigo,
                    'FechaInicio' => is_null($data->RegLote_id) ? "" :  $data->RegLote->FechaInicio,
                    'FechaRecoleccion' => is_null($data->RegLote_id) ? "" :  $data->RegLote->FechaRecoleccion,
                    'Destino' => $data->DestinoServicio,
                    'Labor' =>  is_null($data->Labor_id) ? "" :  $data->Labor->labor,
                    'DetalleLabor' => $data->DetalleLabor,
                    'Cantidad' => $data->Cantidad,
                    'UnidadMedida' => is_null($data->UnidadMedida_id) ? "" :  $data->UnidadMedida->UnidadMedida,
                    'PrecioUnitario' => $data->ValorUnit,
                    'Total' => $data->Total,
                    'Autorizado_identificacion' => is_null($data->CumplidoOrdenServicio->Autorizacion_id) ? "" : $data->CumplidoOrdenServicio->Empleados->identificacion,
                    'Autorizado_nombre' => is_null($data->CumplidoOrdenServicio->Autorizacion_id) ? "" :  $data->CumplidoOrdenServicio->Empleados->nombre1,
                    'factura' => $data->CumplidoOrdenServicio->factura,
                    'fecha_verificado' => $data->CumplidoOrdenServicio->fecha_cierre,

                ];
            });




        return $cumplidoOrdenServicio;
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
        $sheet->setCellValue('C1', 'Contratista Identificacion');
        $sheet->setCellValue('D1', 'Contratista Nombre');
        $sheet->setCellValue('E1', 'Tipo Centro Costo');
        $sheet->setCellValue('F1', 'Distrito');
        $sheet->setCellValue('G1', 'Zona');
        $sheet->setCellValue('H1', 'Finca');
        $sheet->setCellValue('I1', 'Lote');
        $sheet->setCellValue('J1', 'Codigo Lote');
        $sheet->setCellValue('K1', 'Fecha Ini Lote');
        $sheet->setCellValue('L1', 'Fecha Rec Lote');
        $sheet->setCellValue('M1', 'Destino');
        $sheet->setCellValue('N1', 'Labor');
        $sheet->setCellValue('O1', 'DetalleLabor');
        $sheet->setCellValue('P1', 'Cantidad');
        $sheet->setCellValue('Q1', 'UnidadMedida');
        $sheet->setCellValue('R1', 'PrecioUnitario');
        $sheet->setCellValue('S1', 'Total');
        $sheet->setCellValue('T1', 'Autorizado_identificacion');
        $sheet->setCellValue('Y1', 'Autorizado_nombre');
        $sheet->setCellValue('V1', 'factura');
        $sheet->setCellValue('W1', 'fecha_verificado');


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

        $spreadsheet->getActiveSheet()->getStyle('A1:W1')->applyFromArray($styleFila1);


        // Escribir los datos en el archivo Excel
        $row = 2;
        foreach ($CumplidoAplicacion as $cumplido) {
            $sheet->setCellValue('A' . $row, $cumplido['cumplido']);
            $sheet->setCellValue('B' . $row, $cumplido['fecha']);
            $sheet->setCellValue('C' . $row, $cumplido['contratista_identificacion']);
            $sheet->setCellValue('D' . $row, $cumplido['contratista_nombre']);
            $sheet->setCellValue('E' . $row, $cumplido['TipoCentroCosto']);
            $sheet->setCellValue('F' . $row, $cumplido['Distrito']);
            $sheet->setCellValue('G' . $row, $cumplido['Zona']);
            $sheet->setCellValue('H' . $row, $cumplido['Finca']);
            $sheet->setCellValue('I' . $row, $cumplido['Lote']);
            $sheet->setCellValue('J' . $row, $cumplido['CodigoLote']);
            $sheet->setCellValue('K' . $row, $cumplido['FechaInicio']);
            $sheet->setCellValue('L' . $row, $cumplido['FechaRecoleccion']);
            $sheet->setCellValue('M' . $row, $cumplido['Destino']);

            $sheet->setCellValue('N' . $row, $cumplido['Labor']);
            $sheet->setCellValue('O' . $row, $cumplido['DetalleLabor']);
            $sheet->setCellValue('P' . $row, $cumplido['Cantidad']);
            $sheet->setCellValue('Q' . $row, $cumplido['UnidadMedida']);
            $sheet->setCellValue('R' . $row, $cumplido['PrecioUnitario']);
            $sheet->setCellValue('S' . $row, $cumplido['Total']);

            $sheet->setCellValue('T' . $row, $cumplido['Autorizado_identificacion']);
            $sheet->setCellValue('U' . $row, $cumplido['Autorizado_nombre']);
            $sheet->setCellValue('V' . $row, $cumplido['factura']);
            $sheet->setCellValue('W' . $row, $cumplido['fecha_verificado']);


            $row++;
        }

        $spreadsheet->getActiveSheet()->getStyle('A2:W' . $row)->applyFromArray($styleFilas);


        // Configurar la respuesta para descargar el archivo
        $fileName = 'cumplidos_OrdenServicio.xlsx';
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

        if (!is_null($request->selectedCumplidos) && !empty($request->selectedCumplidos)) {
            foreach ($request->selectedCumplidos as $row) {
                if (empty($request['factura'])) {
                    $CumplidoOrdenServicio =  CumplidoOrdenServicio::where('id', $row['id'])->update([
                        'factura' => null,  // Asignar el número de factura a este "cumplido"
                        'fecha_cierre' => null,
                    ]);
                } else {
                    $CumplidoOrdenServicio =  CumplidoOrdenServicio::where('id', $row['id'])->update([
                        'factura' => $request['factura'],  // Asignar el número de factura a este "cumplido"
                        'fecha_cierre' => Carbon::now()->format('Y/m/d'),
                    ]);
                }
            }
        } else {
            $CumplidoOrdenServicio =  CumplidoOrdenServicio::where('id', $request['CumplidoOrdenServicio'])->firstOrFail();

            if (empty($request['factura'])) {
                $CumplidoOrdenServicio->factura = null;
                $CumplidoOrdenServicio->fecha_cierre = null;
            } else {
                $CumplidoOrdenServicio->factura =  $request['factura'];
                $CumplidoOrdenServicio->fecha_cierre = Carbon::now()->format('Y/m/d');
            }
            $CumplidoOrdenServicio->save();
        }

        return redirect(route('CumplidoOrdenServicio.Explorar'))->with('success', 'Cumplido Orden Servicio Verificado correctamente');
    }
}
