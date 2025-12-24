<?php

namespace Database\Seeders\Config\TipoCumplido;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoCumplidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Registro
        $TipoCumplido = [
            [
                'TipoCumplido' => "Cumplido Labores Campo",
                'slug' => Str::slug('Cumplido Labores Campo')  // Slug
            ],
            [
                'TipoCumplido' => "Cumplido Maquinaria",
                'slug' => Str::slug('Cumplido Maquinaria') // Slug
            ],
            [
                'TipoCumplido' => "Cumplido Orden Servicio",
                'slug' => Str::slug('Cumplido Orden Servicio') // Slug
            ],
            [
                'TipoCumplido' => "Cumplido Aplicacion",
                'slug' => Str::slug('Cumplido Aplicacion') // Slug
            ],
        ];
        // Crear Registros
        DB::table('tipo_cumplidos')->insert($TipoCumplido);
    }
}
