<?php

namespace Database\Seeders\Config\Status;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $Registro = [
            [
                'status' => "Activo",
            ],
            [
                'status' => "Bloqueado",
            ],
            [
                'status' => "Cerrado",
            ],
        ];
        // Crear Registros
        DB::table('statuses')->insert($Registro);
    }
}
