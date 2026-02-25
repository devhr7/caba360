<?php

namespace Database\Seeders\Config\UnidadMedida;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UnidadMedidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Registro
        $UnidadMedida = [
            [
                'UnidadMedida' => "Kilo",
                'slug' => Str::slug('Kilo')  // Slug
            ],
            [
                'UnidadMedida' => "Libra",
                'slug' => Str::slug('Libra')  // Slug
            ],
            [
                'UnidadMedida' => "Tonelada",
                'slug' => Str::slug('Tonelada')  // Slug
            ],
            [
                'UnidadMedida' => "Litro",
                'slug' => Str::slug('Litro')  // Slug
            ],
            [
                'UnidadMedida' => "Unidad",
                'slug' => Str::slug('Unidad')  // Slug
            ],
            [
                'UnidadMedida' => "Metro",
                'slug' => Str::slug('Metro')  // Slug
            ],
            [
                'UnidadMedida' => "Metro cuadrado",
                'slug' => Str::slug('Metro cuadrado')  // Slug
            ],
            [
                'UnidadMedida' => "Caneca",
                'slug' => Str::slug('Caneca')  // Slug
            ],
            [
                'UnidadMedida' => "Jornal",
                'slug' => Str::slug('Jornal')  // Slug
            ],
            [
                'UnidadMedida' => "Bulto",
                'slug' => Str::slug('Bulto')  // Slug
            ],
            [
                'UnidadMedida' => "Hectarea",
                'slug' => Str::slug('Hectarea')  // Slug
            ],

        ];
        // Crear Registros
        DB::table('unidad_medidas')->insert($UnidadMedida);
    }
}
