<?php

namespace Database\Seeders\Config\CentroCosto;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClasificacionCentroCostoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         // Registro
         $claseCentroCosto = [
            [
                'ClaseCentroCosto' => "Finca",
                'slug' => Str::slug('Finca')  // Slug
            ],
            [
                'ClaseCentroCosto' => "Lote",
                'slug' => Str::slug('Lote')  // Slug
            ],
            [
                'ClaseCentroCosto' => "Arriendo",
                'slug' => Str::slug('Arriendo') // Slug
            ],
            [
                'ClaseCentroCosto' => "Personal",
                'slug' => Str::slug('Personal') // Slug
            ],
            [
                'ClaseCentroCosto' => "Ganaderia",
                'slug' => Str::slug('Ganaderia') // Slug
            ],
            [
                'ClaseCentroCosto' => "Transporte",
                'slug' => Str::slug('Transporte') // Slug
            ],


        ];
        // Crear Registros
        DB::table('clasificacion_centro_costos')->insert($claseCentroCosto);
    }
}
