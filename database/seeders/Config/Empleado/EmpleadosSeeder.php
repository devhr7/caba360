<?php

namespace Database\Seeders\Config\Empleado;

use App\Models\Cargo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class EmpleadosSeeder extends Seeder
{
    /**
     * Run the database seeds
     */
    public function run(): void
    {
        //

        // Registro
        $cargos = [
            /**
             * Operario de Maquinaria
             */
            [
                'identificacion' => "93384819",
                'nombre1' => "Castellanos Barahona Marco Tulio",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Castellanos Barahona Marco Tulio')  // Slug
            ],
            [
                'identificacion' => "5969751",
                'nombre1' => "Ducuara Ducuara Juan Bautista",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Ducuara Ducuara Juan Bautista')  // Slug
            ],
            [
                'identificacion' => "2361088",
                'nombre1' => "Oviedo Devia Jorge Enrique",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Oviedo Devia Jorge Enrique')  // Slug
            ],
            [
                'identificacion' => "1108232627",
                'nombre1' => "Garcia Franco Plinio",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Garcia Franco Plinio')  // Slug
            ],
            [
                'identificacion' => "5821629",
                'nombre1' => "Mahecha Rojas Paul Fernando",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Mahecha Rojas Paul Fernando')  // Slug
            ],
            [
                'identificacion' => "93389017",
                'nombre1' => "Perdomo Botache Nestor Javier",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Perdomo Botache Nestor Javier')  // Slug
            ],
            [
                'identificacion' => "5975971",
                'nombre1' => "Osorio Santos Jaime",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Osorio Santos Jaime')  // Slug
            ],
            [
                'identificacion' => "93362717",
                'nombre1' => "Barreto Perdomo German",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Barreto Perdomo German')  // Slug
            ],
            [
                'identificacion' => "93235903",
                'nombre1' => "Osorio Cala Josepth Alejandro",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Osorio Cala Josepth Alejandro')  // Slug
            ],
            [
                'identificacion' => "93402121",
                'nombre1' => "Perez Arteaga Nelson Enrique",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Perez Arteaga Nelson Enrique')  // Slug
            ],
            [
                'identificacion' => "11223898",
                'nombre1' => "Oviedo Rueda Alexy",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Oviedo Rueda Alexy')  // Slug
            ],

            [
                'identificacion' => "5968552",
                'nombre1' => "Timote Jose Edilberto",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Timote Jose Edilberto')  // Slug
            ],
            [
                'identificacion' => "14273527",
                'nombre1' => "Guarin Rico Belisario",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Guarin Rico Belisario')  // Slug
            ],
            [
                'identificacion' => "14106887",
                'nombre1' => "Santofimio Castro German Eduardo",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Santofimio Castro German Eduardo')  // Slug
            ],
            [
                'identificacion' => "14273577",
                'nombre1' => "Bocanegra Serrezuela Juan Pablo",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Bocanegra Serrezuela Juan Pablo')  // Slug
            ],
            [
                'identificacion' => "14190722",
                'nombre1' => "Trujillo Cumaco Jerley",
                'cargo_id' =>  Cargo::where("cargo", "Operario Maquinaria")->first()->id,
                'slug' => Str::slug('Trujillo Cumaco Jerley')  // Slug
            ],
            ['identificacion' => '14278577',     'nombre1' => 'MENDOZA PINTO SANTOS', 'cargo_id' => Cargo::where('cargo', 'Operario Maquinaria')->first()->id, 'slug' => Str::slug('MENDOZA PINTO SANTOS')],

            // Oficios Varios
            ['identificacion' => '1007811997', 'nombre1' => 'ALVAREZ VESGA BRAYAN CAMILO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('ALVAREZ VESGA BRAYAN CAMILO')],
            ['identificacion' => '2231420', 'nombre1' => 'ANGEL VARGAS MIGUEL', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('ANGEL VARGAS MIGUEL')],
            ['identificacion' => '5937228', 'nombre1' => 'ARGUELLES GARCIA  JOSE FERNEY', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('ARGUELLES GARCIA  JOSE FERNEY')],
            ['identificacion' => '93379670', 'nombre1' => 'AYALA VICTOR MANUEL', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('AYALA VICTOR MANUEL')],
            ['identificacion' => '1001437664', 'nombre1' => 'BETANCURT ORTIZ CARLOS ARTURO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('BETANCURT ORTIZ CARLOS ARTURO')],
            ['identificacion' => '93359718', 'nombre1' => 'CARRETERO GUTIERREZ JOSE ESAIR', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('CARRETERO GUTIERREZ JOSE ESAIR')],
            ['identificacion' => '1110060554', 'nombre1' => 'CARRILLO MURILLO LUIS FERNANDO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('CARRILLO MURILLO LUIS FERNANDO')],
            ['identificacion' => '13392004', 'nombre1' => 'CARRILLO MURILLO VICTOR MANUEL', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('CARRILLO MURILLO VICTOR MANUEL')],
            ['identificacion' => '1005717245', 'nombre1' => 'CARVAJAL MAZ DAILER VIANEY', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('CARVAJAL MAZ DAILER VIANEY')],
            ['identificacion' => '1110495733', 'nombre1' => 'CARVAJAL MAZ JAMES ALEXIS', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('CARVAJAL MAZ JAMES ALEXIS')],
            ['identificacion' => '96341767', 'nombre1' => 'CEDEÑO CULMA VALENTIN', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('CEDEÑO CULMA VALENTIN')],
            ['identificacion' => '93403517', 'nombre1' => 'DEVIA GONZALEZ URIEL', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('DEVIA GONZALEZ URIEL')],
            ['identificacion' => '5821075', 'nombre1' => 'DEVIA VILLANUEVA NOEL', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('DEVIA VILLANUEVA NOEL')],
            ['identificacion' => '1110495785', 'nombre1' => 'DIAZ VIÑA ELKIN FERNANDO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('DIAZ VIÑA ELKIN FERNANDO')],
            ['identificacion' => '1104012286', 'nombre1' => 'ESCOBAR ROMERO ELVER EDUARDO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('ESCOBAR ROMERO ELVER EDUARDO')],
            ['identificacion' => '1106714262', 'nombre1' => 'FERRO LOZADA DANIEL FELIPE', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('FERRO LOZADA DANIEL FELIPE')],
            ['identificacion' => '5976025', 'nombre1' => 'GARCIA RENE', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('GARCIA RENE')],
            ['identificacion' => '5828069', 'nombre1' => 'GARCIA ROJAS GERMAN', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('GARCIA ROJAS GERMAN')],
            ['identificacion' => '5837378', 'nombre1' => 'GARZON BARRERO GERMAN', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('GARZON BARRERO GERMAN')],
            ['identificacion' => '5937679', 'nombre1' => 'GIL MORENO ALFREDO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('GIL MORENO ALFREDO')],
            ['identificacion' => '14267881', 'nombre1' => 'GONZALEZ DAGOBERTO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('GONZALEZ DAGOBERTO')],
            ['identificacion' => '5827212', 'nombre1' => 'GUTIERREZ DELGADO LUIS GABRIEL', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('GUTIERREZ DELGADO LUIS GABRIEL')],
            ['identificacion' => '5976364', 'nombre1' => 'HERNANDEZ PINZON RUBEN', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('HERNANDEZ PINZON RUBEN')],
            ['identificacion' => '14225353', 'nombre1' => 'MARTINEZ VARGAS ELICEO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('MARTINEZ VARGAS ELICEO')],
            ['identificacion' => '1005933338', 'nombre1' => 'MEJIA CARRILLO EDWIN ANDRES', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('MEJIA CARRILLO EDWIN ANDRES')],
            ['identificacion' => '93391945', 'nombre1' => 'MEJIA OCHOA OTONIEL', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('MEJIA OCHOA OTONIEL')],
            ['identificacion' => '11303505', 'nombre1' => 'MEJIA YESID', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('MEJIA YESID')],
            ['identificacion' => '5976307', 'nombre1' => 'MENESES MONTENEGRO EDISON', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('MENESES MONTENEGRO EDISON')],
            ['identificacion' => '14238264', 'nombre1' => 'MURILLO SALINAS RAMON', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('MURILLO SALINAS RAMON')],
            ['identificacion' => '1071352308', 'nombre1' => 'NAVARRO GONZALEZ RICHARD ANDRES', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('NAVARRO GONZALEZ RICHARD ANDRES')],
            ['identificacion' => '4884648', 'nombre1' => 'OLAYA HORTA ANCIZAR', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('OLAYA HORTA ANCIZAR')],
            ['identificacion' => '1110561054', 'nombre1' => 'OVIEDO SANCHEZ  JOSE ALEJANDRO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('OVIEDO SANCHEZ  JOSE ALEJANDRO')],
            ['identificacion' => '1108232313', 'nombre1' => 'OVIEDO SANCHEZ JOHN FREDDY', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('OVIEDO SANCHEZ JOHN FREDDY')],
            ['identificacion' => '1106453967', 'nombre1' => 'RAMIREZ GUALTERO JESUS ELIAS', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('RAMIREZ GUALTERO JESUS ELIAS')],
            ['identificacion' => '5976101', 'nombre1' => 'REINOSO GUTIERREZ ARMANDO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('REINOSO GUTIERREZ ARMANDO')],
            ['identificacion' => '10165195', 'nombre1' => 'RENDON RAMIREZ JAIRO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('RENDON RAMIREZ JAIRO')],
            ['identificacion' => '5833294', 'nombre1' => 'REYES CEDANO LUIS EGIDIO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('REYES CEDANO LUIS EGIDIO')],
            ['identificacion' => '2717827', 'nombre1' => 'REYES CEDANO STEVE', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('REYES CEDANO STEVE')],
            ['identificacion' => '74302076', 'nombre1' => 'REYES HERNANDEZ FERNANDO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('REYES HERNANDEZ FERNANDO')],
            ['identificacion' => '5833645', 'nombre1' => 'REYES SEDANO JHON JAIRO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('REYES SEDANO JHON JAIRO')],
            ['identificacion' => '1106227141', 'nombre1' => 'RODRIGUEZ CAICEDO EVER SANTIAGO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('RODRIGUEZ CAICEDO EVER SANTIAGO')],
            ['identificacion' => '93402836', 'nombre1' => 'RODRIGUEZ MUÑOZ HENRY', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('RODRIGUEZ MUÑOZ HENRY')],
            ['identificacion' => '1108232809', 'nombre1' => 'ROJAS ARDILA FLEIDER FERNANDO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('ROJAS ARDILA FLEIDER FERNANDO')],
            ['identificacion' => '93363669', 'nombre1' => 'ROJAS CENEN', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('ROJAS CENEN')],
            ['identificacion' => '5832272', 'nombre1' => 'ROJAS HERMES', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('ROJAS HERMES')],
            ['identificacion' => '1110062458', 'nombre1' => 'ROJAS HERRERA OMAR ARLEY', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('ROJAS HERRERA OMAR ARLEY')],
            ['identificacion' => '5836550', 'nombre1' => 'RONDON YESID', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('RONDON YESID')],
            ['identificacion' => '5832801', 'nombre1' => 'TORRES CORTAZAR PEDRO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('TORRES CORTAZAR PEDRO')],
            ['identificacion' => '1108233254', 'nombre1' => 'TORRES MACHADO JEYN ROXEMBER', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('TORRES MACHADO JEYN ROXEMBER')],
            
            ['identificacion' => '93399480', 'nombre1' => 'VALDERRAMA ROJAS LISANDRO', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('VALDERRAMA ROJAS LISANDRO')],
            ['identificacion' => '1108232390', 'nombre1' => 'VILLANUEVA OVIEDO PABLO ANDERSON', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('VILLANUEVA OVIEDO PABLO ANDERSON')],
            ['identificacion' => '1234639348', 'nombre1' => 'VIÑA ACOSTA YIMI ISMAEL', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('VIÑA ACOSTA YIMI ISMAEL')],
            ['identificacion' => '93122803', 'nombre1' => 'ZARTA GUTIERREZ FREDY', 'cargo_id' => Cargo::where("cargo", "Oficios Varios")->first()->id, 'slug' => Str::slug('ZARTA GUTIERREZ FREDY')],

        ];
        // Crear Registros
        DB::table('empleados')->insert($cargos);
    }
}
