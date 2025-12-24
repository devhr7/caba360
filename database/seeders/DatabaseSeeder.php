<?php

namespace Database\Seeders;

use App\Models\ClasificacionCentroCosto;
use App\Models\gasto;
use Database\Seeders\Config\Cargo\CargoSeeder;
use Database\Seeders\Config\CentroCosto\ClasificacionCentroCostoSeeder;
use Database\Seeders\Config\Contratista\ContratistaSeeder;
use Database\Seeders\Config\Distrito\DistritoSeeder;
use Database\Seeders\Config\Empleado\EmpleadosSeeder;
use Database\Seeders\Config\Finca\FincaSeeder;
use Database\Seeders\Config\GrupoMaquinaria\GrupoMaquinaSeeder;
use Database\Seeders\Config\GrupoMateriaPrima\GrupoMateriaPrimaSeeder;
use Database\Seeders\Config\Labor\LaborSeeder;
use Database\Seeders\Config\Lote\LoteSeeder;
use Database\Seeders\Config\Maquina\MaquinaSeeder;
use Database\Seeders\Config\MateriaPrima\MateriaPrimaSeeder;
use Database\Seeders\Config\Status\StatusSeeder;
use Database\Seeders\Config\TipoCultivo\TipoCultivoSeeder;
use Database\Seeders\Config\TipoCumplido\TipoCumplidoSeeder;
use Database\Seeders\Config\TipoLabor\TipoLaborSeeder;
use Database\Seeders\Config\TipoVariedad\TipoVariedadSeeder;
use Database\Seeders\Config\UnidadMedida\UnidadMedidaSeeder;
use Database\Seeders\Modulos\RegLotes\RegistroLotesSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            StatusSeeder::class,
            UnidadMedidaSeeder::class,
            ClasificacionCentroCostoSeeder::class,
            RolePermissionSeeder::class,
            DistritoSeeder::class,
            FincaSeeder::class,
            LoteSeeder::class,
            ContratistaSeeder::class,
            TipoCultivoSeeder::class,
            TipoLaborSeeder::class,
            TipoCumplidoSeeder::class,
            TipoVariedadSeeder::class,
            GrupoMaquinaSeeder::class,
            GrupoMateriaPrimaSeeder::class,
            MateriaPrimaSeeder::class,
            RegistroLotesSeeder::class,
            LaborSeeder::class,
            CargoSeeder::class,
            EmpleadosSeeder::class,
            MaquinaSeeder::class,
            gastoseeder::class,

        ]);

    }
}
