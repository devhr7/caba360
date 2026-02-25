<?php

namespace Database\Seeders\Config\TipoVariedad;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoVariedadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Registro
        $TipoVariedad = [
            [
                'TipoVariedad' => "Cero Labranza",
                'slug' => Str::slug('Cero Labranza')  // Slug
            ],
            [
                'TipoVariedad' => "Orizyca",
                'slug' => Str::slug('Orizyca') // Slug
            ],
            [
                'TipoVariedad' => "Ibis",
                'slug' => Str::slug('Ibis') // Slug
            ],
            [
                'TipoVariedad' => "F68",
                'slug' => Str::slug('F68')  // Slug
            ],
            [
                'TipoVariedad' => "F2000",
                'slug' => Str::slug('F2000')  // Slug
            ],

        ];
        // Crear Registros
        DB::table('tipo_variedads')->insert($TipoVariedad);
    }
}
