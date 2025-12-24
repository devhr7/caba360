<?php

namespace Database\Seeders\Config\Contratista;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContratistaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Registro
                $contratistas = [
                    [
                        'identificacion' => 1110528791,
                        'nombre' => "Luna Gamboa Dayan Dufay",
                        'slug' => Str::slug('Luna Gamboa Dayan Dufay')
                    ],
                    [
                        'identificacion' => 1110450008,
                        'nombre' => "Devia Villanueva Androul",
                        'slug' => Str::slug('Devia Villanueva Androul')
                    ],
                    [
                        'identificacion' => 901839881,
                        'nombre' => "Agrodrones Del Tolima Sas",
                        'slug' => Str::slug('Agrodrones Del Tolima Sas')
                    ],
                    [
                        'identificacion' => 5976249,
                        'nombre' => "Jimenez Saavedra Jose Aldemar",
                        'slug' => Str::slug('Jimenez Saavedra Jose Aldemar')
                    ],
                    [
                        'identificacion' => 5938414,
                        'nombre' => "Lucas Ayala Ricardo",
                        'slug' => Str::slug('Lucas Ayala Ricardo')
                    ],
                    [
                        'identificacion' => 14396973,
                        'nombre' => "Montiel Feria Neiber Alberto",
                        'slug' => Str::slug('Montiel Feria Neiber Alberto')
                    ],
                    [
                        'identificacion' => 14240895,
                        'nombre' => "Moreno Martinez Marco Tulio",
                        'slug' => Str::slug('Moreno Martinez Marco Tulio')
                    ],
                    [
                        'identificacion' => 93389086,
                        'nombre' => "Osorio Urdubay",
                        'slug' => Str::slug('Osorio Urdubay')
                    ],
                    [
                        'identificacion' => 28979381,
                        'nombre' => "Robayo Rodriguez Dirley",
                        'slug' => Str::slug('Robayo Rodriguez Dirley')
                    ],
                    [
                        'identificacion' => 14235391,
                        'nombre' => "Viña Urueña Jorge Enrique",
                        'slug' => Str::slug('Viña Urueña Jorge Enrique')
                    ],
                    [
                        'identificacion' => 1005933036,
                        'nombre' => "Garcia Nieto Dayana Michel",
                        'slug' => Str::slug('Garcia Nieto Dayana Michel')
                    ],
                    [
                        'identificacion' => 28874190,
                        'nombre' => "Gonzalez Guzman Claudia Liliana",
                        'slug' => Str::slug('Gonzalez Guzman Claudia Liliana')
                    ],
                    [
                        'identificacion' => 28796901,
                        'nombre' => "Aldana Castaño Esperanza",
                        'slug' => Str::slug('Aldana Castaño Esperanza')
                    ],
                    [
                        'identificacion' => 901325309,
                        'nombre' => "Agronik Sas Zomac",
                        'slug' => Str::slug('Agronik Sas Zomac')
                    ],

                ];
                // Crear Registros
                DB::table('contratistas')->insert($contratistas);
    }
}
