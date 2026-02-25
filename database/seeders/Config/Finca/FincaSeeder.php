<?php

namespace Database\Seeders\Config\Finca;

use App\Models\Distrito;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FincaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Registro
        $finca = [
            [
                'distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,
                'finca' => "Colombia",
                'metahect' => 32,
                'slug' => Str::of('Colombia')->slug()  // Slug
            ],
            [
                'distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,
                'finca' => "Santa Ana",
                'metahect' => 38,
                'slug' => Str::of('Santa Ana')->slug()  // Slug
            ],
            [
                'distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,
                'finca' => "Coburgos",
                'metahect' => 21,
                'slug' => Str::of('Coburgos')->slug()  // Slug
            ],
            [
                'distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,
                'finca' => "La Matica",
                'metahect' => 13,
                'slug' => Str::of('La Matica')->slug()  // Slug
            ],
            [
                'distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,
                'finca' => "Bustamante",
                'metahect' => 13,
                'slug' => Str::of('Bustamante')->slug()  // Slug
            ],
            [
                'distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,
                'finca' => "Cabras",
                'metahect' => 15,
                'slug' => Str::of('Cabras')->slug()  // Slug
            ],
            [
                'distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,
                'finca' => "Gasconia",
                'metahect' => 5,
                'slug' => Str::of('Gascoña')->slug()  // Slug
            ],
            [
                'distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,
                'finca' => "Recreo",
                'metahect' => 8,
                'slug' => Str::of('Recreo')->slug()  // Slug
            ],
            [
                'distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,
                'finca' => "Arrayan",
                'metahect' => 9,
                'slug' => Str::of('Arrayan')->slug()  // Slug
            ],
            [
                'distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,
                'finca' => "Barbona",
                'metahect' => 32,
                'slug' => Str::of('Barbona')->slug()  // Slug
            ],
            [
                'distrito_id' => Distrito::where("distrito", "Norte")->first()->id,
                'finca' => "La Italia",
                'metahect' => 15,
                'slug' => Str::of('La Italia')->slug()  // Slug
            ],
            [
                'distrito_id' => Distrito::where("distrito", "Norte")->first()->id,
                'finca' => "Alsacia",
                'metahect' => 17,
                'slug' => Str::of('Alsacia')->slug()  // Slug
            ],

        ];
        // Crear Registros
        DB::table('fincas')->insert($finca);
    }
}
