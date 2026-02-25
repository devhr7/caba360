<?php

namespace Database\Seeders\Config\Labor;

use App\Models\TipoCumplido;
use App\Models\TipoLabor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaborSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Cumplido Maquinaria
        //Cumplido Labores Campo

        // Registro
        $Labores = [
            /**
             * Labores Campo
             */
            [
                'labor' => "Moje Rojo",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Labores Campo")->first()->id,
                'CostoHect' => "20000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Moje Rojo')  // Slug
            ],
            [
                'labor' => "Limpia Acequia",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Labores Campo")->first()->id,
                'CostoHect' => "270",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Limpia Acequia')  // Slug
            ],
            [
                'labor' => "Despalille alta",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Labores Campo")->first()->id,
                'CostoHect' => "48000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Despalille alta')  // Slug
            ],
            [
                'labor' => "Fumigacion Canales",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Labores Campo")->first()->id,
                'CostoHect' => "35",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Fumigacion Canales')  // Slug
            ],
            // Producciones

            [
                'labor' => "Produccion <=105 bt",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Labores Campo")->first()->id,
                'CostoHect' => "0",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Produccion <=105 bt')  // Slug
            ],
            [
                'labor' => "Produccion 106-110 bt",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Labores Campo")->first()->id,
                'CostoHect' => "5000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Produccion 106-110 bt')  // Slug
            ],
            [
                'labor' => "Produccion 111-120 bt",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Labores Campo")->first()->id,
                'CostoHect' => "10000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Produccion 111-120 bt')  // Slug
            ],
            [
                'labor' => "Produccion 121-140 bt",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Labores Campo")->first()->id,
                'CostoHect' => "33000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Produccion 121-140 bt')  // Slug
            ],




            /**
             * Cumplido Maquinaria
             */
            [
                'labor' => "Arada Normal",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "3000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Arada Normal'),  // Slug
            ],
            [
                'labor' => "Batido",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "2080",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Batido')  // Slug
            ],
            [
                'labor' => "Braselio Abonada",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "1800",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Braselio Abonada')  // Slug
            ],
            [
                'labor' => "Braselio Fumigada",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "1800",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Braselio Fumigada')  // Slug
            ],
            [
                'labor' => "Caballoneo taipa - Punto",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "2750",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Caballoneo")->first()->id,
                'slug' => Str::slug('Caballoneo taipa - Punto')  // Slug
            ],

            [
                'labor' => "Recaballoneo topografo",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "2080",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Caballoneo")->first()->id,
                'slug' => Str::slug('Recaballoneo topografo')  // Slug
            ],
            [
                'labor' => "Caballoneo topografo M.T.C",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "8600",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Caballoneo")->first()->id,
                'slug' => Str::slug('Caballoneo topografo M.T.C')  // Slug
            ],
            [
                'labor' => "Caleada",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "1600",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Caleada')  // Slug
            ],

            [
                'labor' => "Cincelada",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "10000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Cincelada')  // Slug
            ],
            [
                'labor' => "Desbrosada",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "1600",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Desbrosada')  // Slug
            ],
            [
                'labor' => "Embalconada",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "3000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Embalconada')  // Slug
            ],

            /////////////////////////////////
            [
                'labor' => "Fangueo",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "3500",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Fangueo')  // Slug
            ],

            [
                'labor' => "Hora Retroexcavadora",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "4850",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Hora Retroexcavadora')  // Slug
            ],
            [
                'labor' => "Minima Labranza",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "6000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Minima Labranza')  // Slug
            ],
            [
                'labor' => "Nivelada Land Plane (Hect)",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "3000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Nivelada Land Plane (Hect)')  // Slug
            ],
            [
                'labor' => "Nivelada Laser Rectificar piscina",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "11000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Nivelada Laser Rectificar piscina')  // Slug
            ],
            [
                'labor' => "Nivelada laser piscina (Hect)",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "80000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Nivelada laser piscina (Hect)')  // Slug
            ],
            [
                'labor' => "Rastra",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "3850",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Rastra')  // Slug
            ],
            [
                'labor' => "Siembra",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "6000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Siembra')  // Slug
            ],

            [
                'labor' => "Trailada Paddy (x Bulto)",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "52",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Trailada Paddy (x Bulto)')  // Slug
            ],
            [
                'labor' => "Recolección (x Bulto)",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "90",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Recolección (x Bulto)')  // Slug
            ],
            /**
             * Cumplido de Aplicacion
             */
            [
                'labor' => "Aplicacion Aerea",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Aplicacion")->first()->id,
                'CostoHect' => "61300",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Aplicacion Aerea')  // Slug
            ],
            [
                'labor' => "Aplicacion Dron",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Aplicacion")->first()->id,
                'CostoHect' => "50000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Aplicacion Dron')  // Slug
            ],
            [
                'labor' => "Aplicacion Terrestre",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Aplicacion")->first()->id,
                'CostoHect' => "45000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Aplicacion Terrestre')  // Slug
            ],
            [
                'labor' => "Fertilizacion Aerea",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Aplicacion")->first()->id,
                'CostoHect' => "30600",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Fertilizacion Aerea')  // Slug
            ],
            [
                'labor' => "Fertilizacion Terrestre",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Aplicacion")->first()->id,
                'CostoHect' => "8550",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Fertilizacion Terrestre')  // Slug
            ],
            [
                'labor' => "Fertilizacion Dirigida",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Aplicacion")->first()->id,
                'CostoHect' => "10000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Fertilizacion Dirigida')  // Slug
            ],

            /***
             * Orden de Servicio
             */
            [
                'labor' => "Mezcla Abono",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Orden Servicio")->first()->id,
                'CostoHect' => "2000",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Mezcla Abono')  // Slug
            ],

            [
                'labor' => "Roto Speed",
                'TipoCumplido_id' => TipoCumplido::where("TipoCumplido", "Cumplido Maquinaria")->first()->id,
                'CostoHect' => "1500",
                'TipoLabor_id' => TipoLabor::where("TipoLabor", "Otros Labores")->first()->id,
                'slug' => Str::slug('Roto Speed')  // Slug
            ],





        ];

        // Crear Registros
        DB::table('labors')->insert($Labores);
    }
}
