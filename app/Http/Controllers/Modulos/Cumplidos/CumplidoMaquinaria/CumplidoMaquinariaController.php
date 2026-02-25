<?php

namespace App\Http\Controllers\Modulos\Cumplidos\CumplidoMaquinaria;

/*
|--------------------------------------------------------------------------
| Controlador de CumplidoMaquinaria
|--------------------------------------------------------------------------
|
| Este controlador maneja los cumplidos de maquinaria
|
*/

use App\Http\Controllers\Controller;
use App\Http\Requests\Modulos\Cumplidos\CumplidoMaquinaria\StoreCumplidoMaquinariaRequest;
use App\Http\Requests\Modulos\Cumplidos\CumplidoMaquinaria\UpdateCumplidoMaquinariaRequest;
use App\Models\CumplidoMaquinaria; // Modelos de cumplido maquinaria
use App\Models\Empleados; // Modelos de empleados
use App\Models\Finca; // Modelos de finca
use App\Models\Labor; // Modelos de labor
use App\Models\Lote; // Modelos de lote
use App\Models\Maquina; // Modelos de maquina
use App\Models\RegistroLotes; // Modelos de registro lotes
use Carbon\Carbon; // Libreria para manejar fechas
use Inertia\Inertia; // Libreria para renderizar vistas
use Illuminate\Support\Str; // Libreria para manejar cadenas
use TCPDF; // Libreria para generar PDF
use Illuminate\Support\Facades\Auth; // Libreria para autenticacion
use Illuminate\Support\Facades\Gate; // Libreria para verificacion de permisos
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\Spreadsheet; // Carga La Libreria Excel
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; // Exportar Excel
use PhpOffice\PhpSpreadsheet\IOFactory; // Importar Excel
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use function PHPUnit\Framework\isNull;

class CumplidoMaquinariaController extends Controller
{

    /**
     * Muestra una lista de los cumplidos de maquinaria.
     */
    public function index()
    {
        // Verificar permisos manualmente en el controlador
        // Si no tiene permiso para explorar, se aborta con un status 403
        if (! Gate::allows('mod.cump_aplicacion.show')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        // Consulta
        // Se consulta la tabla de cumplidos de maquinaria y se traen los datos de las relaciones
        // Se seleccionan los campos que se van a mostrar en la vista
        // Se utilizan los modelos para traer los nombres de los campos en lugar de los ids
        // Se utiliza map para transformar los datos en un formato que se pueda mostrar en la vista
        $CumplidoMaquinaria = CumplidoMaquinaria::with(['Distrito:id,distrito', 'Finca:id,finca', 'Lote:id,lote', 'RegistroLotes:id,Codigo', 'Labor:id,labor', 'Maquina:id,CodMaq', 'Empleados'])
            ->select(['id', 'fecha', 'codigo', 'distrito_id', 'finca_id', 'lote_id', 'RegLote_id', 'maquina_id', 'labor_id', 'operario_id', 'cant', 'valor_unit', 'valor_total', 'slug', 'finca', 'lote', 'Externo', 'fecha_cierre', 'verificado'])
            ->orderBy('fecha', 'desc')
            ->get()
            ->map(function ($data) {
            return [
                'id' => $data->id,
                'codigo' => $data->codigo,
                'fecha' => Carbon::parse($data->fecha)->format('d/m/Y'),
                'externo' => $data->Externo,
                'distrito' => is_null($data->distrito_id) ? null : $data->Distrito->distrito,
                'finca' => is_null($data->finca_id) ? $data->finca : $data->Finca->finca . " | " . $data->Lote->lote,
                'codigo_lote' => is_null($data->RegLote_id) ? $data->lote : $data->RegistroLotes->Codigo,
                'operario' => is_null($data->operario_id) ? null : $data->Empleados->nombre1,
                'labor' => is_null($data->labor_id) ? null : $data->Labor->labor,
                'maquinaria' => $data->Maquina->CodMaq,
                'cantidad' => $data->cant,
                'valor_unit' => $data->valor_unit,
                'valor_total' => '$' . number_format($data->valor_total, 0, ',', '.'),
                'fecha_cierre' => is_null($data->fecha_cierre) ? false : $data->fecha_cierre,
                'fecha_cierre_f' => is_null($data->fecha_cierre) ? false : Carbon::parse($data->fecha_cierre)->format('d/m/Y'),
                'verificado' => is_null($data->fecha_cierre) ? false : true,
                'edit_url' => route('CumpMaquinaria.edit', $data->slug),
            ];
            });

        //Renderizar Vista
        // Se renderiza la vista con los datos de los cumplidos de maquinaria y las rutas para crear y editar
        return Inertia::render('Modulos/Cumplidos/CumplidoMaquinaria/Explorar', [
            'CumplidoMaquinaria' => $CumplidoMaquinaria,
            'create_url' => route('CumpMaquinaria.Crear'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar permisos manualmente en el controlador

        if (! Gate::allows('mod.cump_maq.create')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }


        return Inertia::render('Modulos/Cumplidos/CumplidoMaquinaria/Crear', [
            // Traer los datos del distrito para el auto completar
            'get_dataCod' => '',
            'get_finca' => Finca::optionsFinca(),
            'get_lote' => Lote::optionsLote(),
            'get_labor' => Labor::optionsLabor(null, 2,),
            'get_empleado' => Empleados::select(['id', 'identificacion', 'nombre1', 'nombre2', 'apellido1', 'apellido2',  'slug'])->wherein('cargo_id', [1])->get()->map(function ($reg) {
                return [
                    "id" => $reg->id,
                    "label" => $reg->nombre1 . " " . $reg->nombre2 . " " . $reg->apellido1,

                ];
            }),
            'get_maquina' => Maquina::select(['id', 'CodMaq', 'slug'])->get()->map(function ($reg) {
                return [
                    "id" => $reg->id,
                    "label" => $reg->CodMaq,

                ];
            }),
        ]);
    }


    /**
     * Crear un nuevo Cumplido de Maquinaria
     *
     * @param \App\Http\Requests\Modulos\Cumplidos\CumplidoMaquinaria\StoreCumplidoMaquinariaRequest $request
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(StoreCumplidoMaquinariaRequest $request)
    {
        /**
         * * Permiso Creacion de Cumplido de Maquinaria
         */
        $CumplidoMaquinaria = new CumplidoMaquinaria;
        $CumplidoMaquinaria->codigo = Str::trim(Str::squish(Str::upper($request->CodigoCumplido)));
        $CumplidoMaquinaria->fecha = empty($request->FechaCumplido) ? null :  Carbon::parse($request->FechaCumplido)->format('Y-m-d');

        $CumplidoMaquinaria->Externo = $request->Externo;

        // valida si es externo o Interno
        if (boolval($request->Externo)) { // Externo
            $CumplidoMaquinaria->finca = $request->fincaExt;
            $CumplidoMaquinaria->lote = $request->loteExt;
            //  dejar valores null
            $CumplidoMaquinaria->distrito_id = null;
            $CumplidoMaquinaria->finca_id = null;
            $CumplidoMaquinaria->lote_id = null;
            $CumplidoMaquinaria->RegLote_id = null;
        } else { // interno
            $CumplidoMaquinaria->distrito_id =  Finca::where('id', $request->finca['id'])->first()->distrito_id;
            $CumplidoMaquinaria->finca_id = $request->finca['id'];
            $CumplidoMaquinaria->lote_id = $request->lote['id'];
            $CumplidoMaquinaria->RegLote_id = $request->RegLote_id;
            // dejar valores null
            $CumplidoMaquinaria->finca = null;
            $CumplidoMaquinaria->lote = null;
        }

        $CumplidoMaquinaria->maquina_id = $request->Maquina_id['id'];
        $CumplidoMaquinaria->labor_id = $request->labor['id'];
        $CumplidoMaquinaria->valor_unit = $request->labor['CostoHect'];
        $CumplidoMaquinaria->valor_total = doubleval($request->labor['CostoHect']) * doubleval($request->Cant);
        $CumplidoMaquinaria->tipolabor_id = 1;
        $CumplidoMaquinaria->operario_id = $request->empleado['id'];
        $CumplidoMaquinaria->HorometroInicial = $request->horometro_inicio;
        $CumplidoMaquinaria->HorometroFinal = $request->horometro_fin;

        $CumplidoMaquinaria->HorometroTotal = doubleval($request->horometro_fin) - doubleval($request->horometro_inicio);
        $CumplidoMaquinaria->GalCombustible = $request->GalACPM;

        $CumplidoMaquinaria->cant = $request->Cant;

        $CumplidoMaquinaria->Observaciones = $request->Observaciones;
        // Crear Nuevo Cumplido
        $CumplidoMaquinaria->save();


        if (in_array($request->labor['id'], [22, 27])) {
            RegistroLotes::where('id', $request->RegLote_id)->update(['FechaSiembra' => Carbon::parse($request->FechaCumplido)->format('Y-m-d')]);
        }

        return redirect(route('CumpMaquinaria.Explorar')); // Redireccionar vista Explorar
    }

    /**
     * Display the specified resource.
     */
    public function show(CumplidoMaquinaria $cumplidoMaquinaria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        // Verificar permisos manualmente en el controlador
        // Si no tiene permiso para editar, se aborta con un status 403
        if (! Gate::allows('mod.cump_maq.show')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        // Consulta
        // Se consulta la tabla de cumplidos de maquinaria y se traen los datos de las relaciones
        // Se seleccionan los campos que se van a mostrar en la vista
        // Se utilizan los modelos para traer los nombres de los campos en lugar de los ids
        // Se utiliza map para transformar los datos en un formato que se pueda mostrar en la vista
        $CumplidoMaquinaria = CumplidoMaquinaria::where('slug', $slug)->firstOrFail();

        return Inertia::render('Modulos/Cumplidos/CumplidoMaquinaria/Edit', [
            // Traer los datos del cumplimiento

            'datos' => [
                'id' => $CumplidoMaquinaria->id,
                'codigo' => $CumplidoMaquinaria->Codigo,
                'fecha' => $CumplidoMaquinaria->fecha,
                'Finca' => is_null($CumplidoMaquinaria->finca_id) ? null : Finca::optionsFinca($CumplidoMaquinaria->distrito_id, $CumplidoMaquinaria->finca_id),
                'lote' => is_null($CumplidoMaquinaria->lote_id) ? null : Lote::optionsLote($CumplidoMaquinaria->finca_id, $CumplidoMaquinaria->lote_id),

                'RegLote_id' =>  $CumplidoMaquinaria->RegLote_id,
                'Codigolote' =>  RegistroLotes::optionsRegLote(null, null, $CumplidoMaquinaria->RegLote_id),
                //'Codigolote' =>  RegistroLotes::optionsRegLote($CumplidoMaquinaria->lote_id, $CumplidoMaquinaria->RegLote_id),
                'Operario' => [
                    'id' => $CumplidoMaquinaria->operario_id, // Asegúrate de usar el campo correcto
                    "label" => Empleados::where("id", $CumplidoMaquinaria->operario_id)->first()->nombre1  . " " . Empleados::where("id", $CumplidoMaquinaria->operario_id)->first()->nombre2   . " " . Empleados::where("id", $CumplidoMaquinaria->operario_id)->first()->apellido1, //
                ],
                'Cod_Maquina' => [
                    'id' => $CumplidoMaquinaria->maquina_id, // Asegúrate de usar el campo correcto
                    "label" => Maquina::where("id", $CumplidoMaquinaria->maquina_id)->first()->CodMaq //
                ],
                'Labor' => Labor::optionsLabor(null, null, $CumplidoMaquinaria->labor_id),
                'HorometroInicial' => $CumplidoMaquinaria->HorometroInicial,
                'HorometroFinal' => $CumplidoMaquinaria->HorometroFinal,
                'GalACPM' => $CumplidoMaquinaria->GalCombustible,
                'Cant' => $CumplidoMaquinaria->cant,
                'total' => $CumplidoMaquinaria->valor_total,
                'Observaciones' => $CumplidoMaquinaria->Observaciones,
                'Externo' => $CumplidoMaquinaria->Externo,
                'fincaExt' => $CumplidoMaquinaria->Finca,
                'loteExt' => $CumplidoMaquinaria->Lote,
                'slug' => $CumplidoMaquinaria->slug,
                'update_url' => route('CumpMaquinaria.update', $CumplidoMaquinaria->slug),
                'ExportPDFindiv_url' => route('CumpMaquinaria.ExportPDFindiv', $CumplidoMaquinaria->slug),
            ],
            'options' => [
                'get_finca' => Finca::optionsFinca(),
                'get_lote' => is_null($CumplidoMaquinaria->lote_id) ? null : Lote::optionsLote($CumplidoMaquinaria->finca_id),

            ],
            'get_finca' => Finca::all()->map(function ($reg) {
                return [
                    "id" => $reg->id,
                    "label" => $reg->finca
                ];
            }),
            'get_lote' => Lote::all()->map(function ($reg) {
                return [
                    "id" => $reg->id,
                    "label" => $reg->lote
                ];
            }),
            'get_labor' => Labor::all()->map(function ($reg) {
                return [
                    "id" => $reg->id,
                    "label" => $reg->labor . " | " . '$' . number_format($reg->CostoHect, 0, ',', '.'),
                    "CostoHect" => $reg->CostoHect,
                ];
            }),
            /**
             * Se digita el id de los cargos que se desea seleccionar para las labores de maquinaria
             */
            'get_empleado' => Empleados::select(['id', 'identificacion', 'nombre1', 'nombre2', 'apellido1', 'apellido2',  'slug'])->wherein('cargo_id', [1])->get()->map(function ($reg) {
                return [
                    "id" => $reg->id,
                    "label" => $reg->nombre1 . " " . $reg->nombre2 . " " . $reg->apellido1,

                ];
            }),
            'get_maquina' => Maquina::select(['id', 'CodMaq', 'slug'])->get()->map(function ($reg) {
                return [
                    "id" => $reg->id,
                    "label" => $reg->CodMaq,

                ];
            }),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCumplidoMaquinariaRequest $request, $slug)
    {

        $CumplidoMaquinaria = CumplidoMaquinaria::where('slug', $slug)->firstOrFail();
        $CumplidoMaquinaria->codigo = Str::trim(Str::squish(Str::upper($request->CodigoCumplido)));
        $CumplidoMaquinaria->fecha = empty($request->FechaCumplido) ? null :  Carbon::parse($request->FechaCumplido)->format('Y-m-d');

        $CumplidoMaquinaria->Externo = $request->Externo;

        // valida si es externo o Interno
        if (boolval($request->Externo)) { // Externo
            $CumplidoMaquinaria->finca = $request->fincaExt;
            $CumplidoMaquinaria->lote = $request->loteExt;
            //  dejar valores null
            $CumplidoMaquinaria->distrito_id = null;
            $CumplidoMaquinaria->finca_id = null;
            $CumplidoMaquinaria->lote_id = null;
            $CumplidoMaquinaria->RegLote_id = null;
        } else { // interno
            $CumplidoMaquinaria->distrito_id =  Finca::where('id', $request->finca['id'])->first()->distrito_id;
            $CumplidoMaquinaria->finca_id = $request->finca['id'];
            $CumplidoMaquinaria->lote_id = $request->lote['id'];
            $CumplidoMaquinaria->RegLote_id = $request->RegLote_id;
            // dejar valores null
            $CumplidoMaquinaria->finca = null;
            $CumplidoMaquinaria->lote = null;
        }

        $CumplidoMaquinaria->maquina_id = $request->Maquina_id['id'];
        $CumplidoMaquinaria->labor_id = $request->labor['id'];
        $CumplidoMaquinaria->valor_unit = $request->labor['CostoHect'];
        $CumplidoMaquinaria->valor_total = doubleval($request->labor['CostoHect']) * doubleval($request->Cant);
        $CumplidoMaquinaria->tipolabor_id = 1;
        $CumplidoMaquinaria->operario_id = $request->empleado['id'];
        $CumplidoMaquinaria->HorometroInicial = $request->horometro_inicio;
        $CumplidoMaquinaria->HorometroFinal = $request->horometro_fin;
        $CumplidoMaquinaria->GalCombustible = $request->GalACPM;
        $CumplidoMaquinaria->cant = $request->Cant;
        $CumplidoMaquinaria->HorometroTotal = doubleval($request->horometro_fin) - doubleval($request->horometro_inicio);
        $CumplidoMaquinaria->Observaciones = $request->Observaciones;

        $CumplidoMaquinaria->save();
    }


    public function destroy($slug)
    {
        if (! Gate::allows('mod.cump_maq.delete')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        $CumplidoMaquinaria =  CumplidoMaquinaria::where('slug', $slug)->firstOrFail();
        $CumplidoMaquinaria->delete();
        return redirect(route('CumpMaquinaria.Explorar'));
    }



    /**
     * Exporta Individual el PDF
     */
    public function ExportPDFindiv($slug)
    {

        // Verificar permisos manualmente en el controlador

        if (! Gate::allows('CumplidoMaquinaria.Exportar')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
    }





    public function Exportar()
    {


        //Renderizar Vista
        // Se renderiza la vista con los datos de los cumplidos de maquinaria y las rutas para crear y editar
        return Inertia::render('Modulos/Cumplidos/CumplidoMaquinaria/Exportar', [
            'get_finca' => Finca::all()->map(function ($reg) {
                return [
                    "id" => $reg->id,
                    "label" => $reg->finca
                ];
            }),
        ]);
    }

    /**
     * Exportar cumplidos de maquinaria en un archivo Excel (xlsx)
     *
     * @return \Illuminate\Http\Response
     */
    public function exportXLSXpost(Request $request)
    {
        $CumplidoMaquinaria = CumplidoMaquinaria::with(['Finca:id,finca', 'RegistroLotes:id,Hect,Codigo', 'Lote:id,lote', 'Maquina:id,CodMaq', 'Empleados:id,nombre1,identificacion'])
            ->select('id', 'slug', 'Codigo', 'distrito_id', 'finca_id', 'lote_id', 'RegLote_id', 'maquina_id', 'labor_id', 'fecha', 'tipolabor_id', 'operario_id', 'HorometroInicial', 'HorometroFinal', 'GalCombustible', 'cant', 'valor_unit', 'valor_total');
        if (!is_null($request->fecha_inicio) && !is_null($request->fecha_fin)) {
            $fecha_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d');
            $fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');
            $CumplidoMaquinaria = $CumplidoMaquinaria->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
        }
        if (!is_null($request->finca)) {
            $finca = $request->finca['id'];
            $CumplidoMaquinaria = $CumplidoMaquinaria->where('finca_id', $finca);
        }
        if (!is_null($request->lote)) {
            $lote = $request->lote['id'];
            $CumplidoMaquinaria = $CumplidoMaquinaria->where('lote_id', $lote);
        }
        if (!is_null($request->reglote)) {
            $reglote = $request->reglote['id'];
            $CumplidoMaquinaria = $CumplidoMaquinaria->where('RegLote_id', $reglote);
        }

        $CumplidoMaquinaria = $CumplidoMaquinaria->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'codigo' => $data->Codigo,
                    'fecha' => $data->fecha,
                    'CodigoLote' => $data->RegistroLotes->Codigo,
                    'representative' => [
                        'name' => $data->RegistroLotes->Codigo
                    ],
                    'finca_id' => $data->finca_id,
                    'finca' => $data->Finca->finca,
                    'lote_id' => $data->lote_id,
                    'lote' => $data->Lote->lote,
                    'hect' => $data->RegistroLotes->Hect,
                    'maquina' => $data->Maquina->CodMaq,
                    'HorometroInicial' => $data->HorometroInicial,
                    'HorometroFinal' => $data->HorometroFinal,
                    'GalCombustible' => $data->GalCombustible,
                    'IdentificacionOperario' => $data->Empleados->identificacion,
                    'operario' => $data->Empleados->nombre1,
                    'labor' => $data->Labor->labor,
                    'cantidad' => $data->cant,
                    'valor_unit' => $data->valor_unit,
                    'valor_total' => $data->valor_total,
                ];
            });


        // Crear un nuevo objeto Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados de las columnas
        $sheet->setCellValue('A1', 'Cumplido');
        $sheet->setCellValue('B1', 'Fecha Cumplido');
        $sheet->setCellValue('C1', 'Codigo Lote');
        $sheet->setCellValue('D1', 'Finca');
        $sheet->setCellValue('E1', 'Lote');
        $sheet->setCellValue('F1', 'Hect Lote');
        $sheet->setCellValue('G1', 'Maquina');
        $sheet->setCellValue('H1', 'Identifciacion Operario');
        $sheet->setCellValue('I1', 'Operario');
        $sheet->setCellValue('J1', 'Labor');
        $sheet->setCellValue('K1', 'Cantidad');
        $sheet->setCellValue('L1', 'Valor Unitario');
        $sheet->setCellValue('M1', 'Valor Total');

        // Escribir los datos en el archivo Excel
        $row = 2;
        foreach ($CumplidoMaquinaria as $cumplido) {
            $sheet->setCellValue('A' . $row, $cumplido['codigo']);
            $sheet->setCellValue('B' . $row, $cumplido['fecha']);
            $sheet->setCellValue('C' . $row, $cumplido['CodigoLote']);
            $sheet->setCellValue('D' . $row, $cumplido['finca']);
            $sheet->setCellValue('E' . $row, $cumplido['lote']);
            $sheet->setCellValue('F' . $row, $cumplido['hect']);
            $sheet->setCellValue('G' . $row, $cumplido['maquina']);
            $sheet->setCellValue('H' . $row, $cumplido['IdentificacionOperario']);
            $sheet->setCellValue('I' . $row, $cumplido['operario']);
            $sheet->setCellValue('J' . $row, $cumplido['labor']);
            $sheet->setCellValue('K' . $row, $cumplido['cantidad']);
            $sheet->setCellValue('L' . $row, $cumplido['valor_unit']);
            $sheet->setCellValue('M' . $row, $cumplido['valor_total']);
            $row++;
        }

        // Configurar la respuesta para descargar el archivo
        $fileName = 'cumplidos_maquinaria_.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/' . $fileName));

        // Descargar el archivo Excel
        return response()->download(storage_path('app/public/' . $fileName), $fileName, [
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ])->deleteFileAfterSend(true);
    }
    /**
     * Consulta los cumplidos de maquinaria con los filtros de fecha inicio, fecha fin, finca, lote y registro lote.
     * Los datos se pueden filtrar por fecha inicio y fin en formato 'd/m/Y'.
     * Los datos se pueden filtrar por finca, lote y registro lote con el id de la finca, lote y registro lote respectivamente.
     * La respuesta se devuelve en formato JSON con los campos id, codigo, fecha, CodigoLote, finca_id, finca, lote_id, lote, hect, maquina, HorometroInicial, HorometroFinal, GalCombustible, IdentificacionOperario, operario, labor, cantidad, valor_unit y valor_total.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function consulta(Request $request)
    {
        $CumplidoMaquinaria = CumplidoMaquinaria::with(['Finca:id,finca', 'RegistroLotes:id,Hect,Codigo', 'Lote:id,lote', 'Maquina:id,CodMaq', 'Empleados:id,nombre1,identificacion'])
            ->select('id', 'slug', 'Codigo', 'distrito_id', 'finca_id', 'lote_id', 'RegLote_id', 'maquina_id', 'labor_id', 'fecha', 'tipolabor_id', 'operario_id', 'HorometroInicial', 'HorometroFinal', 'GalCombustible', 'cant', 'valor_unit', 'valor_total');
        if (!is_null($request->fecha_inicio) && !is_null($request->fecha_fin)) {
            $fecha_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d');
            $fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');
            $CumplidoMaquinaria = $CumplidoMaquinaria->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
        }

        if (!is_null($request->finca)) {
            $finca = $request->finca['id'];
            $CumplidoMaquinaria = $CumplidoMaquinaria->where('finca_id', $finca);
        }
        if (!is_null($request->lote)) {
            $lote = $request->lote['id'];
            $CumplidoMaquinaria = $CumplidoMaquinaria->where('lote_id', $lote);
        }
        if (!is_null($request->reglote)) {
            $reglote = $request->reglote['id'];
            $CumplidoMaquinaria = $CumplidoMaquinaria->where('RegLote_id', $reglote);
        }

        $CumplidoMaquinaria = $CumplidoMaquinaria->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'codigo' => $data->Codigo,
                    'fecha' => $data->fecha,
                    'CodigoLote' => $data->RegistroLotes->Codigo,
                    'representative' => [
                        'name' => $data->RegistroLotes->Codigo
                    ],
                    'finca_id' => $data->finca_id,
                    'finca' => $data->Finca->finca,
                    'lote_id' => $data->lote_id,
                    'lote' => $data->Lote->lote,
                    'hect' => $data->RegistroLotes->Hect,
                    'maquina' => $data->Maquina->CodMaq,
                    'HorometroInicial' => $data->HorometroInicial,
                    'HorometroFinal' => $data->HorometroFinal,
                    'GalCombustible' => $data->GalCombustible,
                    'IdentificacionOperario' => $data->Empleados->identificacion,
                    'operario' => $data->Empleados->nombre1,
                    'labor' => $data->Labor->labor,
                    'cantidad' => $data->cant,
                    'valor_unit' => $data->valor_unit,
                    'valor_total' => $data->valor_total,

                ];
            });

        return $CumplidoMaquinaria;
    }



    public function dataconsulta()
    {
        $CumplidoMaquinaria = CumplidoMaquinaria::with(['Finca:id,finca', 'RegistroLotes:id,Hect,Codigo', 'Lote:id,lote', 'Maquina:id,CodMaq', 'Empleados:id,nombre1,identificacion'])
            ->selectRaw('finca_id, SUM(valor_total) as valor_total')
            ->groupBy('finca_id')
            ->get()
            ->map(function ($data) {
                return [
                    'id' => $data->finca_id,
                    'finca' => $data->Finca->finca,
                    'valor_total' => $data->valor_total,
                    'labels' => $data->Finca->finca,
                    'values' => $data->valor_total

                ];
            });

        $data = [
            'labels' => $CumplidoMaquinaria->pluck('labels')->toArray(),
            'values' => $CumplidoMaquinaria->pluck('values')->toArray(),
        ];

        return response()->json($data);
    }







    /** Reporte */

    public function report()
    {
        // Verificar permisos manualmente en el controlador
        return inertia::render('Modulos/Cumplidos/CumplidoMaquinaria/Report', []);
    }
    public function DataReport(Request $request)
    {
        $fecha_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');
        $CumplidoMaquinaria1 = CumplidoMaquinaria::with(['Distrito', 'Finca', 'Lote', 'RegistroLotes', 'TipoLabor', 'Labor', 'Empleados', 'Maquina'])->whereBetween('fecha', [$fecha_inicio, $fecha_fin])->get();


        $CumplidoMaquinaria = CumplidoMaquinaria::with(['Distrito', 'Finca', 'Lote', 'RegistroLotes', 'TipoLabor', 'Labor', 'Empleados', 'Maquina'])->whereBetween('fecha', [$fecha_inicio, $fecha_fin])->get()
            ->map(function ($data) {
                return [
                    'cumplido' => $data->Codigo,
                    'fecha' => Carbon::parse($data->fecha)->format('d/m/Y'),
                    'Externo_Interno' => boolval($data->Externo) ? 'Externo' : 'Interno',
                    'Distrito' => is_null($data->finca_id) || is_null($data->finca) || is_null($data->finca->distrito) ? null : $data->finca->distrito->distrito,
                    'Zona' => is_null($data->finca_id) || is_null($data->finca) || is_null($data->finca->zona) ? null : $data->finca->zona->zona,
                    'Finca' => is_null($data->finca_id) ? $data->Finca : $data->finca->finca,
                    'Lote' => is_null($data->lote_id) ? $data->Lote : $data->lote->lote,
                    'CodigoLote' => is_null($data->RegLote_id) ? '' : $data->RegistroLotes->Codigo,
                'Fecha_ini_Lote' => is_null($data->RegLote_id) ? '' : $data->RegistroLotes->FechaInicio,
                'Fecha_Rec_Lote' => is_null($data->RegLote_id) ? '' : $data->RegistroLotes->FechaRecoleccion,
                    'Hect' => is_null($data->RegLote_id) ? '' : $data->RegistroLotes->Hect,
                    'IdentificacionOperario' => $data->Empleados->identificacion,
                    'Operario' => $data->Empleados->nombre1,
                    'Labor' => $data->Labor->labor,
                    'Maquina' => $data->Maquina->CodMaq,
                    'HorometroInicial' => $data->HorometroInicial,
                    'HorometroFinal' => $data->HorometroFinal,
                    'HorometroTotal' => $data->HorometroTotal,
                    'GalCombustible' => $data->GalCombustible,
                    'Cantidad' => $data->cant,
                    'valor_unit' => $data->valor_unit,
                    'valor_total' => $data->valor_total,
                    'garantia' => $data->garantia,
                    'observaciones' => $data->observaciones,
                    'verificado' => $data->verificado,
                    'fecha_cierre' => $data->fecha_cierre,

                ];
            });


        return $CumplidoMaquinaria;
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
        $sheet->setCellValue('C1', 'Externo/Interno');
        $sheet->setCellValue('D1', 'Distrito');
        $sheet->setCellValue('E1', 'Zona');
        $sheet->setCellValue('F1', 'Finca');
        $sheet->setCellValue('G1', 'Lote');
        $sheet->setCellValue('H1', 'Codigo Lote');
        $sheet->setCellValue('I1', 'Fecha_ini_Lote');
        $sheet->setCellValue('J1', 'Fecha_Rec_Lote');
        $sheet->setCellValue('K1', 'Hectareas Lote');
        $sheet->setCellValue('L1', 'Identificacion Operario');
        $sheet->setCellValue('M1', 'Nombre Operario');
        $sheet->setCellValue('N1', 'Maquina');
        $sheet->setCellValue('O1', 'Horometro Inicial');
        $sheet->setCellValue('P1', 'Horometro Final');
        $sheet->setCellValue('Q1', 'Total Horas');
        $sheet->setCellValue('R1', 'Galones Combustible');
        $sheet->setCellValue('S1', 'Labor');
        $sheet->setCellValue('T1', 'Cantidad/Hect Laboradas');
        $sheet->setCellValue('U1', 'Precio Unit');
        $sheet->setCellValue('V1', 'Total');
        $sheet->setCellValue('W1', 'Observaciones');
        $sheet->setCellValue('X1', 'verificado');



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

        $spreadsheet->getActiveSheet()->getStyle('A1:X1')->applyFromArray($styleFila1);


        // Escribir los datos en el archivo Excel
        $row = 2;
        foreach ($CumplidoAplicacion as $cumplido) {
            $sheet->setCellValue('A' . $row, $cumplido['cumplido']);
            $sheet->setCellValue('B' . $row, $cumplido['fecha']);
            $sheet->setCellValue('C' . $row, $cumplido['Externo_Interno']);
            $sheet->setCellValue('D' . $row, $cumplido['Distrito']);
            $sheet->setCellValue('E' . $row, $cumplido['Zona']);
            $sheet->setCellValue('F' . $row, $cumplido['Finca']);
            $sheet->setCellValue('G' . $row, $cumplido['Lote']);
            $sheet->setCellValue('H' . $row, $cumplido['CodigoLote']);
            $sheet->setCellValue('I' . $row, $cumplido['Fecha_ini_Lote']);
            $sheet->setCellValue('J' . $row, $cumplido['Fecha_Rec_Lote']);
            $sheet->setCellValue('K' . $row, $cumplido['Hect']);
            $sheet->setCellValue('L' . $row, $cumplido['IdentificacionOperario']);
            $sheet->setCellValue('M' . $row, $cumplido['Operario']);
            $sheet->setCellValue('N' . $row, $cumplido['Maquina']);
            $sheet->setCellValue('O' . $row, $cumplido['HorometroInicial']);
            $sheet->setCellValue('P' . $row, $cumplido['HorometroFinal']);
            $sheet->setCellValue('Q' . $row, $cumplido['HorometroTotal']);
            $sheet->setCellValue('R' . $row, $cumplido['GalCombustible']);
            $sheet->setCellValue('S' . $row, $cumplido['Labor']);
            $sheet->setCellValue('T' . $row, $cumplido['Cantidad']);
            $sheet->setCellValue('U' . $row, $cumplido['valor_unit']);
            $sheet->setCellValue('V' . $row, $cumplido['valor_total']);
            $sheet->setCellValue('W' . $row, $cumplido['observaciones']);
            $sheet->setCellValue('X' . $row, $cumplido['fecha_cierre']);

            $row++;
        }

        $spreadsheet->getActiveSheet()->getStyle('A2:X' . $row)->applyFromArray($styleFilas);


        // Configurar la respuesta para descargar el archivo
        $fileName = 'cumplidos_maquinaria.xlsx';
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
                $CumplidoMaquinaria =  CumplidoMaquinaria::where('id', $request->cumplido)->update([
                    'fecha_cierre' => null,  // Asignar el número de factura a este "cumplido"
                    'verificado' => false,
                ]);
            } else {
                $CumplidoMaquinaria =  CumplidoMaquinaria::where('id', $request->cumplido)->update([
                    'fecha_cierre' => Carbon::parse($request->fechaCierre)->format('Y-m-d'),  // Asignar el número de factura a este "cumplido"
                    'verificado' => true,
                ]);
            }
        } elseif (isset($request->selectedCumplidos) && !empty($request->selectedCumplidos)) {
            foreach ($request->selectedCumplidos as $row) {
                if (!isset($request['fechaCierre']) || empty($request['fechaCierre'])) {
                    $CumplidoMaquinaria =  CumplidoMaquinaria::where('id', $row['id'])->update([
                        'fecha_cierre' => null,  //
                        'verificado' => false,
                    ]);
                } else {
                    $CumplidoMaquinaria =  CumplidoMaquinaria::where('id', $row['id'])->update([
                        'fecha_cierre' => Carbon::parse($request->fechaCierre)->format('Y-m-d'),  // Asignar el número de factura a este "cumplido"
                        'verificado' => true,
                    ]);
                }
            }
        }

        //$CumplidoMaquinaria->save();
        return redirect(route('CumpMaquinaria.Explorar'))->with('success', 'Cumplido Maquinaria Verificado correctamente');
    }
}
