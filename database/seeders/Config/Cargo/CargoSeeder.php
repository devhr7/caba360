<?php

namespace Database\Seeders\Config\Cargo;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        // Registro
        $cargos = [
            [
                'cargo' => "Operario Maquinaria",
                'slug' => Str::slug('Operario Maquinaria')  // Slug
            ],
            [
                'cargo' => "Oficios Varios",
                'slug' => Str::slug('Oficios Varios') // Slug
            ],
            [
                'cargo' => "Coord Costo y ppt",
                'slug' => Str::slug('Coord Costo y ppt') // Slug
            ]
        ];
        // Crear Registros
        DB::table('cargos')->insert($cargos);
    }
}
