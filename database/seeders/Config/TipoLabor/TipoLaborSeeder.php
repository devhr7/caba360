<?php

namespace Database\Seeders\Config\TipoLabor;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoLaborSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Registro
       $TipoLabor = [
        [
            'TipoLabor' => "Rastra",
            'slug' => Str::slug('Rastra')  // Slug
        ],
        [
            'TipoLabor' => "Niveladora",
            'slug' => Str::slug('Niveladora')  // Slug
        ],
        [
            'TipoLabor' => "Caballoneo",
            'slug' => Str::slug('Caballoneo')  // Slug
        ],
        [
            'TipoLabor' => "Siembra",
            'slug' => Str::slug('Siembra')  // Slug
        ],
        [
            'TipoLabor' => "Otros Labores",
            'slug' => Str::slug('Otros Labores')  // Slug
        ],
    ];
    // Crear Registros
    DB::table('tipo_labors')->insert($TipoLabor);
    }
}
