<?php

namespace Database\Seeders;

use App\Models\gasto;
use App\Models\TipoGasto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GastoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** Gastos */
        $gastos = [
            [
                'slug' => Str::slug('Arriendos'),  // Slug
                'codigo' => "1",
                'nombre' => "Arriendos",
            ],
            [
                'slug' => Str::slug('Financieros'),  // Slug
                'codigo' => "2",
                'nombre' => "Financieros",
            ],
            [
                'slug' => Str::slug('Finca'),  // Slug
                'codigo' => "3",
                'nombre' => "Finca",
            ],
            [
                'slug' => Str::slug('Grupo'),  // Slug
                'codigo' => "4",
                'nombre' => "Grupo",
            ],
            [
                'slug' => Str::slug('Maquinaria'),  // Slug
                'codigo' => "5",
                'nombre' => "Maquinaria",
            ],
            [
                'slug' => Str::slug('Oficina'),  // Slug
                'codigo' => "7",
                'nombre' => "Oficina",
            ],
            [
                'slug' => Str::slug('MateriaPrima'),  // Slug
                'codigo' => "6",
                'nombre' => "Materia Prima",
            ],
            [
                'slug' => Str::slug('ServiciosAgropecuarios'),  // Slug
                'codigo' => "8",
                'nombre' => "Servicios Agropecuarios",
            ],

        ];
        DB::table('gastos')->insert($gastos);

        /** TipoGasto */


        /** Sub TipoGasto */
        $Tipogasto = [
            [
                'slug' => Str::slug('Arriendos'),  // Slug
                'codigo' => "10",
                'nombre' => "Arriendos",
                'gasto_id' => gasto::where('nombre', 'Arriendos')->first()->id,
            ],
            [
                'slug' => Str::slug('Financieros'),  // Slug
                'codigo' => "19",
                'nombre' => "Financieros",
                'gasto_id' => gasto::where('nombre', 'Financieros')->first()->id,
            ],
            [
                'slug' => Str::slug('Finca'),  // Slug
                'codigo' => "16",
                'nombre' => "Finca",
                'gasto_id' => gasto::where('nombre', 'Finca')->first()->id,
            ],
            [
                'slug' => Str::slug('Grupo'),  // Slug
                'codigo' => "18",
                'nombre' => "Grupo",
                'gasto_id' => gasto::where('nombre', 'Grupo')->first()->id,
            ],

            [
                'slug' => Str::slug('Maquinaria'),  // Slug
                'codigo' => "15",
                'nombre' => "Maquinaria",
                'gasto_id' => gasto::where('nombre', 'Maquinaria')->first()->id,
            ],




            [
                'slug' => Str::slug('Oficina'),  // Slug
                'codigo' => "17",
                'nombre' => "Oficina",
                'gasto_id' => gasto::where('nombre', 'Oficina')->first()->id,
            ],
            [
                'slug' => Str::slug('Foliares'),  // Slug
                'codigo' => "7",
                'nombre' => "Foliares",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
            ],
            [
                'slug' => Str::slug('Pegantes'),  // Slug
                'codigo' => "9",
                'nombre' => "Pegantes",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
            ],
            [
                'slug' => Str::slug('Insecticidas'),  // Slug
                'codigo' => "6",
                'nombre' => "Insecticidas",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
            ],
            [
                'slug' => Str::slug('Fungicidas'),  // Slug
                'codigo' => "4",
                'nombre' => "Fungicidas",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
            ],
            [
                'slug' => Str::slug('Herbicidas'),  // Slug
                'codigo' => "5",
                'nombre' => "Herbicidas",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
            ],
            [
                'slug' => Str::slug('Semillas'),  // Slug
                'codigo' => "8",
                'nombre' => "Semillas",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
            ],
            [
                'slug' => Str::slug('Abonos'),  // Slug
                'codigo' => "3",
                'nombre' => "Abonos",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
            ],
            [
                'slug' => Str::slug('ServiciosAgropecuarios'),  // Slug
                'codigo' => "11",
                'nombre' => "Servicios Agropecuarios",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
            ],
        ];

        DB::table('tipo_gastos')->insert($Tipogasto);

        // Crear Registros
        $subtipogasto = [
            [
                'slug' => Str::slug('10-5'),  // Slug
                'codigo1' => "5",
                'codigo' => "10-5",
                'gasto_id' => gasto::where('nombre', 'Arriendos')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Arriendos')->first()->id,
                'nombre' => "Arriendo A",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('10-6'),  // Slug
                'codigo1' => "6",
                'codigo' => "10-6",
                'gasto_id' => gasto::where('nombre', 'Arriendos')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Arriendos')->first()->id,
                'nombre' => "Arriendo B",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('10-11'),  // Slug
                'codigo1' => "11",
                'codigo' => "10-11",
                'gasto_id' => gasto::where('nombre', 'Arriendos')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Arriendos')->first()->id,
                'nombre' => "Regulacion A",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('10-12'),  // Slug
                'codigo1' => "12",
                'codigo' => "10-12",
                'gasto_id' => gasto::where('nombre', 'Arriendos')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Arriendos')->first()->id,
                'nombre' => "Regulacion B",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('10-13'),  // Slug
                'codigo1' => "13",
                'codigo' => "10-13",
                'gasto_id' => gasto::where('nombre', 'Arriendos')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Arriendos')->first()->id,
                'nombre' => "Regulacion Cargas",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('19-1'),  // Slug
                'codigo1' => "1",
                'codigo' => "19-1",
                'gasto_id' => gasto::where('nombre', 'Financieros')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Financieros')->first()->id,
                'nombre' => "Financieros",
                'valorunitario' => null,
            ],
            // Finca
            [
                'slug' => Str::slug('16-1'),  // Slug
                'codigo1' => "1",
                'codigo' => "16-1",
                'gasto_id' => gasto::where('nombre', 'Finca')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Finca')->first()->id,
                'nombre' => "Finca",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('16-5'),  // Slug
                'codigo1' => "5",
                'codigo' => "16-5",
                'gasto_id' => gasto::where('nombre', 'Finca')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Finca')->first()->id,
                'nombre' => "Turbo",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('16-6'),  // Slug
                'codigo1' => "6",
                'codigo' => "16-6",
                'gasto_id' => gasto::where('nombre', 'Finca')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Finca')->first()->id,
                'nombre' => "RetroExcavadora",
                'valorunitario' => null,
            ],
            // Grupo
            [
                'slug' => Str::slug('18-1'),  // Slug
                'codigo1' => "1",
                'codigo' => "18-1",
                'gasto_id' => gasto::where('nombre', 'Grupo')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Grupo')->first()->id,
                'nombre' => "Fee Operativo",
                'valorunitario' => null,
            ],
            // Oficina
            [
                'slug' => Str::slug('17-1'),  // Slug
                'codigo1' => "1",
                'codigo' => "17-1",
                'gasto_id' => gasto::where('nombre', 'Oficina')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Oficina')->first()->id,
                'nombre' => "Oficina",
                'valorunitario' => null,
            ],

            // Servicios Agropecuarios
            [
                'slug' => Str::slug('11-124'),  // Slug
                'codigo1' => "124",
                'codigo' => "11-124",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'nombre' => "Sacada Piedra",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('11-127'),  // Slug
                'codigo1' => "127",
                'codigo' => "11-127",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'nombre' => "Quema Con Vara",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('11-129'),  // Slug
                'codigo1' => "129",
                'codigo' => "11-129",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'nombre' => "Quema Canal",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('11-133'),  // Slug
                'codigo1' => "133",
                'codigo' => "11-133",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'nombre' => "Fumigacion Dron",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('11-18'),  // Slug
                'codigo1' => "18",
                'codigo' => "11-18",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'nombre' => "Guadana",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('11-2'),  // Slug
                'codigo1' => "2",
                'codigo' => "11-2",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'nombre' => "Abonada Aerea",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('11-20'),  // Slug
                'codigo1' => "20",
                'codigo' => "11-20",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'nombre' => "Despalille",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('11-29'),  // Slug
                'codigo1' => "29",
                'codigo' => "11-29",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'nombre' => "Limpia Acequias",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('11-3'),  // Slug
                'codigo1' => "3",
                'codigo' => "11-3",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'nombre' => "Fumigacion Terrestre",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('11-38'),  // Slug
                'codigo1' => "38",
                'codigo' => "11-38",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'nombre' => "Cuota Fomento",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('11-46'),  // Slug
                'codigo1' => "46",
                'codigo' => "11-46",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'nombre' => "Riego",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('11-50'),  // Slug
                'codigo1' => "50",
                'codigo' => "11-50",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'nombre' => "Siembra",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('11-58'),  // Slug
                'codigo1' => "58",
                'codigo' => "11-58",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'nombre' => "Mezcla Abono",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('11-6'),  // Slug
                'codigo1' => "6",
                'codigo' => "11-6",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'nombre' => "Abonada Terrestre",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('11-7'),  // Slug
                'codigo1' => "7",
                'codigo' => "11-7",
                'gasto_id' => gasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Servicios Agropecuarios')->first()->id,
                'nombre' => "Transportecod",
                'valorunitario' => null,
            ],
            // Maquinaria
            [
                'slug' => Str::slug('15-3'),  // Slug
                'codigo1' => "3",
                'codigo' => "15-3",
                'gasto_id' => gasto::where('nombre', 'Maquinaria')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Maquinaria')->first()->id,
                'nombre' => "Rastra",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('15-6'),  // Slug
                'codigo1' => "6",
                'codigo' => "15-6",
                'gasto_id' => gasto::where('nombre', 'Maquinaria')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Maquinaria')->first()->id,
                'nombre' => "Niveladora",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('15-11'),  // Slug
                'codigo1' => "11",
                'codigo' => "15-11",
                'gasto_id' => gasto::where('nombre', 'Maquinaria')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Maquinaria')->first()->id,
                'nombre' => "Caballoneo",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('15-12'),  // Slug
                'codigo1' => "12",
                'codigo' => "15-12",
                'gasto_id' => gasto::where('nombre', 'Maquinaria')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Maquinaria')->first()->id,
                'nombre' => "Siembra",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('15-9'),  // Slug
                'codigo1' => "9",
                'codigo' => "15-9",
                'gasto_id' => gasto::where('nombre', 'Maquinaria')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Maquinaria')->first()->id,
                'nombre' => "Cincelada",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('15-1'),  // Slug
                'codigo1' => "1",
                'codigo' => "15-1",
                'gasto_id' => gasto::where('nombre', 'Maquinaria')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Maquinaria')->first()->id,
                'nombre' => "Fee Maquinaria",
                'valorunitario' => null,
            ],


            // Materia Prima
            [
                'slug' => Str::slug('9-6'), // Slug
                'codigo1' => "6",
                'codigo' => "9-6",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Pegantes')->first()->id,
                'nombre' => "DASH",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('9-59'), // Slug
                'codigo1' => "59",
                'codigo' => "9-59",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Pegantes')->first()->id,
                'nombre' => "PORTADOR",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('9-77'), // Slug
                'codigo1' => "77",
                'codigo' => "9-77",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Pegantes')->first()->id,
                'nombre' => "SYSCOMET",
                'valorunitario' => null,
            ],

            [
                'slug' => Str::slug('6-67'), // Slug
                'codigo1' => "67",
                'codigo' => "6-67",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Insecticidas')->first()->id,
                'nombre' => "ENGEO",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('6-72'), // Slug
                'codigo1' => "72",
                'codigo' => "6-72",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Insecticidas')->first()->id,
                'nombre' => "EXALT60SC",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('6-161'), // Slug
                'codigo1' => "161",
                'codigo' => "6-161",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Insecticidas')->first()->id,
                'nombre' => "DACONIL",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('6-165'), // Slug
                'codigo1' => "165",
                'codigo' => "6-165",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Insecticidas')->first()->id,
                'nombre' => "KASUMIN",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('6-166'), // Slug
                'codigo1' => "166",
                'codigo' => "6-166",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Insecticidas')->first()->id,
                'nombre' => "TRISICLASOL",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('6-167'), // Slug
                'codigo1' => "167",
                'codigo' => "6-167",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Insecticidas')->first()->id,
                'nombre' => "CRUICERARROZ",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('6-176'), // Slug
                'codigo1' => "176",
                'codigo' => "6-176",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Insecticidas')->first()->id,
                'nombre' => "BINGO",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('6-193'), // Slug
                'codigo1' => "193",
                'codigo' => "6-193",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Insecticidas')->first()->id,
                'nombre' => "NEFASTO",
                'valorunitario' => null,
            ],

            [
                'slug' => Str::slug('5-1'), // Slug
                'codigo1' => "1",
                'codigo' => "5-1",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "AURA",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-3'), // Slug
                'codigo1' => "3",
                'codigo' => "5-3",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "2.4DEAMINA",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-20'), // Slug
                'codigo1' => "20",
                'codigo' => "5-20",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "PROPANIL",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-24'), // Slug
                'codigo1' => "24",
                'codigo' => "5-24",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "RIFIT",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-26'), // Slug
                'codigo1' => "26",
                'codigo' => "5-26",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "RONSTARFLO38",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-36'), // Slug
                'codigo1' => "36",
                'codigo' => "5-36",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "TORDON",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-42'), // Slug
                'codigo1' => "42",
                'codigo' => "5-42",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "ATRAZINA",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-43'), // Slug
                'codigo1' => "43",
                'codigo' => "5-43",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "STAM80DG",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-44'), // Slug
                'codigo1' => "44",
                'codigo' => "5-44",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "GESAPRIM",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-103'), // Slug
                'codigo1' => "103",
                'codigo' => "5-103",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "ROUNDUPBRIO",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-106'), // Slug
                'codigo1' => "106",
                'codigo' => "5-106",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "PARAQUAT",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-123'), // Slug
                'codigo1' => "123",
                'codigo' => "5-123",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "GAMIT",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-215'), // Slug
                'codigo1' => "215",
                'codigo' => "5-215",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "LINAP",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-234'), // Slug
                'codigo1' => "234",
                'codigo' => "5-234",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "HEAT",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-244'), // Slug
                'codigo1' => "244",
                'codigo' => "5-244",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "QUINCLORAC",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-250'), // Slug
                'codigo1' => "250",
                'codigo' => "5-250",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "CLETODIN",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-256'), // Slug
                'codigo1' => "256",
                'codigo' => "5-256",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "OXYFLUORFEN",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-258'), // Slug
                'codigo1' => "258",
                'codigo' => "5-258",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "ARROW",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-259'), // Slug
                'codigo1' => "259",
                'codigo' => "5-259",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "AMBOX",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-273'), // Slug
                'codigo1' => "273",
                'codigo' => "5-273",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "GAVILAN",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-287'), // Slug
                'codigo1' => "287",
                'codigo' => "5-287",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "COVERADVANCE",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-289'), // Slug
                'codigo1' => "289",
                'codigo' => "5-289",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "LOYANT",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-291'), // Slug
                'codigo1' => "291",
                'codigo' => "5-291",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "EUROLIGHTNINGBASF",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-293'), // Slug
                'codigo1' => "293",
                'codigo' => "5-293",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "ODYSSEY480EC",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-300'), // Slug
                'codigo1' => "300",
                'codigo' => "5-300",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "ACETOGAN",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-305'), // Slug
                'codigo1' => "305",
                'codigo' => "5-305",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "MAYORAL350SL",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('5-308'), // Slug
                'codigo1' => "308",
                'codigo' => "5-308",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Herbicidas')->first()->id,
                'nombre' => "ATRANEX500",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('3-1'), // Slug
                'codigo1' => "1",
                'codigo' => "3-1",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Abonos')->first()->id,
                'nombre' => "KCL",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('3-4'), // Slug
                'codigo1' => "4",
                'codigo' => "3-4",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Abonos')->first()->id,
                'nombre' => "KELATEXZN",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('3-7'), // Slug
                'codigo1' => "7",
                'codigo' => "3-7",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Abonos')->first()->id,
                'nombre' => "SAM",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('3-9'), // Slug
                'codigo1' => "9",
                'codigo' => "3-9",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Abonos')->first()->id,
                'nombre' => "UREA",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('3-16'), // Slug
                'codigo1' => "16",
                'codigo' => "3-16",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Abonos')->first()->id,
                'nombre' => "KELATEXBORO",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('3-19'), // Slug
                'codigo1' => "19",
                'codigo' => "3-19",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Abonos')->first()->id,
                'nombre' => "KELATEXCOBRE",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('3-40'), // Slug
                'codigo1' => "40",
                'codigo' => "3-40",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Abonos')->first()->id,
                'nombre' => "DIMAZOS",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('3-48'), // Slug
                'codigo1' => "48",
                'codigo' => "3-48",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Abonos')->first()->id,
                'nombre' => "KELATEXHIERRO",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('3-83'), // Slug
                'codigo1' => "83",
                'codigo' => "3-83",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Abonos')->first()->id,
                'nombre' => "MICROESCENCIAL",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('3-157'), // Slug
                'codigo1' => "157",
                'codigo' => "3-157",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Abonos')->first()->id,
                'nombre' => "NITRO-XTEN-S",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('3-158'), // Slug
                'codigo1' => "158",
                'codigo' => "3-158",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Abonos')->first()->id,
                'nombre' => "BULCANOAZUFRE",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('3-188'), // Slug
                'codigo1' => "188",
                'codigo' => "3-188",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Abonos')->first()->id,
                'nombre' => "AGROFOS",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('3-192'), // Slug
                'codigo1' => "192",
                'codigo' => "3-192",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Abonos')->first()->id,
                'nombre' => "ROBUSTO",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('3-223'), // Slug
                'codigo1' => "223",
                'codigo' => "3-223",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Abonos')->first()->id,
                'nombre' => "VITSOILPRO",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('3-227'), // Slug
                'codigo1' => "227",
                'codigo' => "3-227",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Abonos')->first()->id,
                'nombre' => "NITROSMART",
                'valorunitario' => null,
            ],

            [
                'slug' => Str::slug('4-6'), // Slug
                'codigo1' => "6",
                'codigo' => "4-6",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Fungicidas')->first()->id,
                'nombre' => "FUDIOLAN",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('4-15'), // Slug
                'codigo1' => "15",
                'codigo' => "4-15",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Fungicidas')->first()->id,
                'nombre' => "TASPA",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('4-16'), // Slug
                'codigo1' => "16",
                'codigo' => "4-16",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Fungicidas')->first()->id,
                'nombre' => "AMISTAR",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('4-42'), // Slug
                'codigo1' => "42",
                'codigo' => "4-42",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Fungicidas')->first()->id,
                'nombre' => "NATIVO",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('4-105'), // Slug
                'codigo1' => "105",
                'codigo' => "4-105",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Fungicidas')->first()->id,
                'nombre' => "CAPSIALIL",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('4-124'), // Slug
                'codigo1' => "124",
                'codigo' => "4-124",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Fungicidas')->first()->id,
                'nombre' => "CORAJE",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('4-125'), // Slug
                'codigo1' => "125",
                'codigo' => "4-125",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Fungicidas')->first()->id,
                'nombre' => "TIMOREXGOLD",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('4-133'), // Slug
                'codigo1' => "133",
                'codigo' => "4-133",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Fungicidas')->first()->id,
                'nombre' => "SERENADE",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('4-141'), // Slug
                'codigo1' => "141",
                'codigo' => "4-141",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Fungicidas')->first()->id,
                'nombre' => "MUSACARE",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('4-146'), // Slug
                'codigo1' => "146",
                'codigo' => "4-146",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Fungicidas')->first()->id,
                'nombre' => "ECOSWING",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('4-148'), // Slug
                'codigo1' => "148",
                'codigo' => "4-148",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Fungicidas')->first()->id,
                'nombre' => "MANZATE",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('4-152'), // Slug
                'codigo1' => "152",
                'codigo' => "4-152",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Fungicidas')->first()->id,
                'nombre' => "FORWIN",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('4-153'), // Slug
                'codigo1' => "153",
                'codigo' => "4-153",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Fungicidas')->first()->id,
                'nombre' => "BELANTY",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('7-5'), // Slug
                'codigo1' => "5",
                'codigo' => "7-5",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Foliares')->first()->id,
                'nombre' => "TRIFESOL",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('7-63'), // Slug
                'codigo1' => "63",
                'codigo' => "7-63",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Foliares')->first()->id,
                'nombre' => "KELATEXMANGANESO",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('7-117'), // Slug
                'codigo1' => "117",
                'codigo' => "7-117",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Foliares')->first()->id,
                'nombre' => "CALHIDRATADA",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('7-120'), // Slug
                'codigo1' => "120",
                'codigo' => "7-120",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Foliares')->first()->id,
                'nombre' => "FOSFOBIOL",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('7-186'), // Slug
                'codigo1' => "186",
                'codigo' => "7-186",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Foliares')->first()->id,
                'nombre' => "BIOZYME",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('7-230'), // Slug
                'codigo1' => "230",
                'codigo' => "7-230",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Foliares')->first()->id,
                'nombre' => "ACTYBAC",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('7-239'), // Slug
                'codigo1' => "239",
                'codigo' => "7-239",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Foliares')->first()->id,
                'nombre' => "SYNESTRESS",
                'valorunitario' => null,
            ],
            [
                'slug' => Str::slug('7-251'), // Slug
                'codigo1' => "251",
                'codigo' => "7-251",
                'gasto_id' => gasto::where('nombre', 'Materia Prima')->first()->id,
                'tipogasto_id' => TipoGasto::where('nombre', 'Foliares')->first()->id,
                'nombre' => "ISABION",
                'valorunitario' => null,
            ],





        ];
        DB::table('subtipogastos')->insert($subtipogasto);
    }
}
