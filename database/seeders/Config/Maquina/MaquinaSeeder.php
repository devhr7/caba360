<?php

namespace Database\Seeders\Config\Maquina;

use App\Models\GrupoMaquina;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MaquinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Registro
        $maquinaria = [
            // Combinada
            [
                'CodMaq' => "1C1",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Combinada")->first()->id,
                'slug' => Str::slug('1C1')  // Slug
            ],
            [
                'CodMaq' => "1C2",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Combinada")->first()->id,
                'slug' => Str::slug('1C2')  // Slug
            ],
            [
                'CodMaq' => "1C3",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Combinada")->first()->id,
                'slug' => Str::slug('1C3') // Slug
            ],
            [
                'CodMaq' => "1C4",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Combinada")->first()->id,
                'slug' => Str::slug('1C4') // Slug
            ],
            [
                'CodMaq' => "1C5",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Combinada")->first()->id,
                'slug' => Str::slug('1C5') // Slug
            ],
            [
                'CodMaq' => "1C6",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Combinada")->first()->id,
                'slug' => Str::slug('1C6') // Slug
            ],
            // Tractor
            [
                'CodMaq' => "1T13",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T13') // Slug
            ],
            [
                'CodMaq' => "1T1",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T1') // Slug
            ],
            [
                'CodMaq' => "1T17",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T17') // Slug
            ],
            [
                'CodMaq' => "1T15",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T15') // Slug
            ],
            [
                'CodMaq' => "1T8",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T8') // Slug
            ],
            [
                'CodMaq' => "1T20",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T20') // Slug
            ],
            [
                'CodMaq' => "1T16",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T16') // Slug
            ],
            [
                'CodMaq' => "1TB1",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1TB1') // Slug
            ],
            [
                'CodMaq' => "1T3",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T3') // Slug
            ],
            [
                'CodMaq' => "1T10",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T10') // Slug
            ],
            [
                'CodMaq' => "1T9",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T9') // Slug
            ],
            [
                'CodMaq' => "1T18",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T18') // Slug
            ],
            [
                'CodMaq' => "1T7",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T7') // Slug
            ],

            [
                'CodMaq' => "1T14",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T14') // Slug
            ],
            [
                'CodMaq' => "1T19",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T19') // Slug
            ],
            [
                'CodMaq' => "1T4",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T4') // Slug
            ],
            [
                'CodMaq' => "1T5",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T5') // Slug
            ],
            [
                'CodMaq' => "1T11",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T11') // Slug
            ],
            [
                'CodMaq' => "1T21",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T21') // Slug
            ],
            [
                'CodMaq' => "1T6",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T6') // Slug
            ],
            [
                'CodMaq' => "1T12",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T12') // Slug
            ],
            [
                'CodMaq' => "1T22",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Tractor")->first()->id,
                'slug' => Str::slug('1T22') // Slug
            ],
            // Retro Excavadora
            [
                'CodMaq' => "1RT1",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Retroexcavadora")->first()->id,
                'slug' => Str::slug('1RT1') // Slug
            ],
            // Sembradora
            [
                'CodMaq' => "2S6",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Sembradora")->first()->id,
                'slug' => Str::slug('2S6') // Slug
            ],

            // Volqueta
            [
                'CodMaq' => "1A4",
                'GrupoMaq_id' => GrupoMaquina::where("GrupoMaquina", "Volqueta")->first()->id,
                'slug' => Str::slug('1A4q2') // Slug
            ],


        ];
        // Crear Registros
        DB::table('maquinas')->insert($maquinaria);
    }
}
