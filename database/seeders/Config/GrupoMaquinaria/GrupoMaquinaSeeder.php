<?php

namespace Database\Seeders\Config\GrupoMaquinaria;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GrupoMaquinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        // Registro
        $GrupoMaquina = [
            [
                'GrupoMaquina' => "Combinada",
                'slug' => Str::slug('Combinada')  // Slug
            ],
            [
                'GrupoMaquina' => "Tractor",
                'slug' => Str::slug('Tractor')  // Slug
            ],
            [
                'GrupoMaquina' => "Retroexcavadora",
                'slug' => Str::slug('Retroexcavadora')  // Slug
            ],
            [
                'GrupoMaquina' => "Sembradora",
                'slug' => Str::slug('Sembradora')  // Slug
            ],
            [
                'GrupoMaquina' => "Volqueta",
                'slug' => Str::slug('Volqueta')  // Slug
            ],
        ];

        // Crear Registros
        DB::table('grupo_maquinas')->insert($GrupoMaquina);

    }
}
