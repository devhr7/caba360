<?php

namespace Database\Seeders\Config\TipoGasto;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoGastoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         // Registro
         $TipoGasto = [
            [
                'TipoGasto' => "Materia Prima",
                'slug' => Str::slug('Materia Prima')  // Slug
            ],
            [
                'TipoGasto' => "Arriendo",
                'slug' => Str::slug('Arriendo') // Slug
            ],
            [
                'TipoGasto' => "Servicio Agropecuarios",
                'slug' => Str::slug('Servicio Agropecuarios') // Slug
            ],
            [
                'TipoGasto' => "Maquinaria",
                'slug' => Str::slug('Maquinaria') // Slug
            ],
            [
                'TipoGasto' => "Finca",
                'slug' => Str::slug('Finca') // Slug
            ],
            [
                'TipoGasto' => "Financieros",
                'slug' => Str::slug('Financieros') // Slug
            ],
            [
                'TipoGasto' => "Grupo",
                'slug' => Str::slug('Grupo') // Slug
            ],
            [
                'TipoGasto' => "Oficina",
                'slug' => Str::slug('Oficina') // Slug
            ],
        ];
        // Crear Registros
        DB::table('tipo_gastos')->insert($TipoGasto);
    }
}
