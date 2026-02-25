<?php

namespace Database\Seeders\Config\TipoCultivo;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoCultivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Registro
        $TipoCultivo = [
            [
                'TipoCultivo' => "Plantilla",
                'slug' => Str::slug('Plantilla')  // Slug
            ],
            [
                'TipoCultivo' => "Soca",
                'slug' => Str::slug('Soca') // Slug
            ],
            [
                'TipoCultivo' => "Secano",
                'slug' => Str::slug('Secano') // Slug
            ],
            [
                'TipoCultivo' => "Maiz",
                'slug' => Str::slug('Maiz') // Slug
            ],
            [
                'TipoCultivo' => "Sorgo",
                'slug' => Str::slug('Sorgo') // Slug
            ],
            [
                'TipoCultivo' => "Frijol",
                'slug' => Str::slug('Frijol') // Slug
            ]
        ];
        // Crear Registros
        DB::table('tipo_cultivos')->insert($TipoCultivo);
    }
}
