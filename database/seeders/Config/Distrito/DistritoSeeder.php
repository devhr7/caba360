<?php

namespace Database\Seeders\Config\Distrito;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DistritoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Registro
        $distritos = [
            [
                'distrito' => "Meseta",
                'slug' => Str::slug('Meseta')  // Slug
            ],
            [
                'distrito' => "Norte",
                'slug' => Str::slug('Norte') // Slug

            ]
        ];
        // Crear Registros
        DB::table('distritos')->insert($distritos);
    }
}
