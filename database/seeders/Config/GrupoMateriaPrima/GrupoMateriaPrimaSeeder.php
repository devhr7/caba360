<?php

namespace Database\Seeders\Config\GrupoMateriaPrima;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GrupoMateriaPrimaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Registro
        $GrupoMateriaPrima = [
            [
                'GrupoMateriaPrima' => "Abonos",
                'slug' => Str::slug('Abonos')  // Slug
            ],
            [
                'GrupoMateriaPrima' => "Foliares",
                'slug' => Str::slug('Foliares')  // Slug
            ],
            [
                'GrupoMateriaPrima' => "Fungicidas",
                'slug' => Str::slug('Fungicidas')  // Slug
            ],
            [
                'GrupoMateriaPrima' => "Herbicidas",
                'slug' => Str::slug('Herbicidas')  // Slug
            ],
            [
                'GrupoMateriaPrima' => "Insecticidas",
                'slug' => Str::slug('Insecticidas')  // Slug
            ],
            [
                'GrupoMateriaPrima' => "Pegantes",
                'slug' => Str::slug('Pegantes')  // Slug
            ],
            [
                'GrupoMateriaPrima' => "Semillas",
                'slug' => Str::slug('Semillas')  // Slug
            ],



        ];
        // Crear Registros
        DB::table('grupo_materia_primas')->insert($GrupoMateriaPrima);
    }
}
