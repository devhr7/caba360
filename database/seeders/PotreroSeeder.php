<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PotreroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('potreros')->insert([
['finca_id' => 11, 'nombre' => 'Potrero 1'],
['finca_id' => 11, 'nombre' => 'Potrero 2'],
['finca_id' => 11, 'nombre' => 'Potrero 3'],
['finca_id' => 11, 'nombre' => 'Potrero 4'],
['finca_id' => 11, 'nombre' => 'Potrero Bomba'],
['finca_id' => 11, 'nombre' => 'Potrero Sorgiyal'],



['finca_id' => 12, 'nombre' => 'Potrero Pescadero 1'],
['finca_id' => 12, 'nombre' => 'Potrero Pescadero 2'],
['finca_id' => 12, 'nombre' => 'Potrero Pescadero 3'],
['finca_id' => 12, 'nombre' => 'Potrero La Bodega'],
['finca_id' => 12, 'nombre' => 'Potrero Del Bosque'],
['finca_id' => 12, 'nombre' => 'Potrero Las Chivas'],
['finca_id' => 12, 'nombre' => 'Potrero Bellavista'],
['finca_id' => 12, 'nombre' => 'Potrero Ceibo'],

// Hato 13
['finca_id' => 13, 'nombre' => 'Potrero Hato 1'],
['finca_id' => 13, 'nombre' => 'Potrero Hato 2'],
['finca_id' => 13, 'nombre' => 'Potrero Hato 3'],
['finca_id' => 13, 'nombre' => 'Potrero Hato 4'],
['finca_id' => 13, 'nombre' => 'Potrero Hato 5'],
// Cabras 6']
['finca_id' => 6, 'nombre' => 'Potrero Salto 1'],
['finca_id' => 6, 'nombre' => 'Potrero Salto 2'],
['finca_id' => 6, 'nombre' => 'Potrero Salto 3'],
['finca_id' => 6, 'nombre' => 'Potrero Mangas -Toro'],
['finca_id' => 6, 'nombre' => 'Potrero Mangas - Corral'],
['finca_id' => 6, 'nombre' => 'Potrero Galindo'],
['finca_id' => 6, 'nombre' => 'Potrero Represa 1'],
['finca_id' => 6, 'nombre' => 'Potrero Represa 2'],

// Coburgos 3'
['finca_id' => 3, 'nombre' => 'Potrero Patalo'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Maternidad 1'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Maternidad 2'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Maternidad 3'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Gimnasio'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Del 5'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Pioquinta'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Del 6'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Del Silo'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Puente 8a'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Punte 8b'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Punte 8c'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Punte 8d'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Puente 9a'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Puente 9b'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Lote 10a'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Lote  10b'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Lote 12a'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Lote 12b'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Lote 12c'],
['finca_id' => 3, 'nombre' => 'Potrero Manga 13a'],
['finca_id' => 3, 'nombre' => 'Potrero Manga 13b'],
['finca_id' => 3, 'nombre' => 'Potrero Manga 13c'],
['finca_id' => 3, 'nombre' => 'Potrero Horno'],
['finca_id' => 3, 'nombre' => 'Potrero Mirador'],
['finca_id' => 3, 'nombre' => 'Potrero Tamarindo'],
['finca_id' => 3, 'nombre' => 'Potrero Balastrera'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Lote 11a'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Lote 11b'],
['finca_id' => 3, 'nombre' => 'Potrero Lago'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Del  14'],
['finca_id' => 3, 'nombre' => 'Potrero Manga Del  15'],
['finca_id' => 3, 'nombre' => 'Potrero Monos 1'],
['finca_id' => 3, 'nombre' => 'Potrero Monos 2'],
['finca_id' => 3, 'nombre' => 'Potrero Monos 3'],
['finca_id' => 3, 'nombre' => 'Potrero Cardon 1 Y 2'],
['finca_id' => 3, 'nombre' => 'Potrero Cardon 3'],
['finca_id' => 3, 'nombre' => 'Potrero Cardon 4'],
['finca_id' => 3, 'nombre' => 'Potrero Cardon 5'],
['finca_id' => 3, 'nombre' => 'Potrero Cardon 6'],
['finca_id' => 3, 'nombre' => 'Potrero Manga 1'],
// Santa Ana 2'
['finca_id' => 2, 'nombre' => 'Potrero Manga 5'],
['finca_id' => 2, 'nombre' => 'Potrero Manga 6'],
['finca_id' => 2, 'nombre' => 'Potrero Manga 7'],
['finca_id' => 2, 'nombre' => 'Potrero Manga 8'],
['finca_id' => 2, 'nombre' => 'Potrero Manga Represa 3'],
['finca_id' => 2, 'nombre' => 'Potrero Manga Marranera'],
['finca_id' => 2, 'nombre' => 'Potrero Manga Aldemar'],
['finca_id' => 2, 'nombre' => 'Potrero Manga 18'],
// Matica 4']
['finca_id' => 4, 'nombre' => 'Potrero Manga Perrera'],
['finca_id' => 4, 'nombre' => 'Potrero Manga Arenero'],
['finca_id' => 4, 'nombre' => 'Potrero Manga Bahia'],
['finca_id' => 4, 'nombre' => 'Potrero Manga Escondida'],
['finca_id' => 4, 'nombre' => 'Potrero Diomate 1'],
['finca_id' => 4, 'nombre' => 'Potrero Diomate 2'],
['finca_id' => 4, 'nombre' => 'Potrero Manga Del 17'],
['finca_id' => 4, 'nombre' => 'Potrero Manga La Matica Margen Izquierda'],
['finca_id' => 4, 'nombre' => 'Potrero Manga La Matica Margen Derecho'],
['finca_id' => 4, 'nombre' => 'Potrero Manga Lote 13'],
// Bustamante 5']
['finca_id' => 5, 'nombre' => 'Potrero Loma Larga 1'],
['finca_id' => 5, 'nombre' => 'Potrero Loma Larga 2'],
['finca_id' => 5, 'nombre' => 'Potrero Loma Larga 3'],
['finca_id' => 5, 'nombre' => 'Potrero Loma Larga 4'],
['finca_id' => 5, 'nombre' => 'Potrero Loma Larga 5'],
['finca_id' => 5, 'nombre' => 'Potrero Loma Larga 6'],
['finca_id' => 5, 'nombre' => 'Potrero Loma Larga 7'],
['finca_id' => 5, 'nombre' => 'Potrero Piragua'],
['finca_id' => 5, 'nombre' => 'Potrero Pangola 1'],
['finca_id' => 5, 'nombre' => 'Potrero Pangola 2'],
['finca_id' => 5, 'nombre' => 'Potrero Manga 60'],
['finca_id' => 5, 'nombre' => 'Potrero Manga Enrique'],





        ]);
    }
}
