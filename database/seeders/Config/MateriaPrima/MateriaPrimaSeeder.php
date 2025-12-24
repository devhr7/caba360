<?php

namespace Database\Seeders\Config\MateriaPrima;

use App\Models\Grupo_MateriaPrima;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MateriaPrimaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Abonos
        // Foliares
        // Fungicidas
        // Herbicidas
        // Insecticidas
        // Pegantes
        // Semillas


        $materiaPrima = [
            // Abonos
            [
                'slug' => Str::slug('BULCANOAZUFRE'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "BULCANOAZUFRE",
                'PrecioUnit' => '76535.6',
            ],
            [
                'slug' => Str::slug('DISANSIEMBRA'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "DISANSIEMBRA",
                'PrecioUnit' => '200670.529',
            ],
            [
                'slug' => Str::slug('KCL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "KCL",
                'PrecioUnit' => '92361.8816',
            ],
            [
                'slug' => Str::slug('KELATEXHIERRO'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "KELATEXHIERRO",
                'PrecioUnit' => '32143.45332',
            ],
            [
                'slug' => Str::slug('KEXLATEZINC'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "KEXLATEZINC",
                'PrecioUnit' => '28100.275',
            ],
            [
                'slug' => Str::slug('MICROESCENCIAL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "MICROESCENCIAL",
                'PrecioUnit' => '160300.0193',
            ],
            [
                'slug' => Str::slug('NITROSMART'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "NITROSMART",
                'PrecioUnit' => '125243.601',
            ],
            [
                'slug' => Str::slug('NUTRIHUMIC'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "NUTRIHUMIC",
                'PrecioUnit' => '65558.20189',
            ],
            [
                'slug' => Str::slug('ROBUSTO'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "ROBUSTO",
                'PrecioUnit' => '132304.8082',
            ],
            [
                'slug' => Str::slug('SAKONZIN'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "SAKONZIN",
                'PrecioUnit' => '119221.5143',
            ],
            [
                'slug' => Str::slug('SAM'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "SAM",
                'PrecioUnit' => '63430.24438',
            ],
            [
                'slug' => Str::slug('SOLUFOS'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "SOLUFOS",
                'PrecioUnit' => '227389.1001',
            ],
            [
                'slug' => Str::slug('UREA'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "UREA",
                'PrecioUnit' => '102813.1423',
            ],
            [
                'slug' => Str::slug('VITSOILPRO'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "VITSOILPRO",
                'PrecioUnit' => '106809.3146',
            ],
            [
                'slug' => Str::slug('AGROFOS'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "AGROFOS",
                'PrecioUnit' => '140070',
            ],
            [
                'slug' => Str::slug('DIMAZOS'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Abonos")->first()->id,
                'MateriaPrima' => "DIMAZOS",
                'PrecioUnit' => '49329',
            ],

            // Foliares
            [
                'slug' => Str::slug('ACONDICIONADOR'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Foliares")->first()->id,
                'MateriaPrima' => "ACONDICIONADOR",
                'PrecioUnit' => '22193.91763',
            ],
            [
                'slug' => Str::slug('ACTYBAC'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Foliares")->first()->id,
                'MateriaPrima' => "ACTYBAC",
                'PrecioUnit' => '110756.8',
            ],
            [
                'slug' => Str::slug('BIOZYME'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Foliares")->first()->id,
                'MateriaPrima' => "BIOZYME",
                'PrecioUnit' => '114322.931',
            ],
            [
                'slug' => Str::slug('CALHIDRATADA'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Foliares")->first()->id,
                'MateriaPrima' => "CALHIDRATADA",
                'PrecioUnit' => '55745.57431',
            ],
            [
                'slug' => Str::slug('CROPBOOSTYARA'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Foliares")->first()->id,
                'MateriaPrima' => "CROPBOOSTYARA",
                'PrecioUnit' => '62471.88801',
            ],
            [
                'slug' => Str::slug('FOSFOBIOL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Foliares")->first()->id,
                'MateriaPrima' => "FOSFOBIOL",
                'PrecioUnit' => '105397.6',
            ],
            [
                'slug' => Str::slug('ISABION'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Foliares")->first()->id,
                'MateriaPrima' => "ISABION",
                'PrecioUnit' => '52428.02491',
            ],
            [
                'slug' => Str::slug('KAFOL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Foliares")->first()->id,
                'MateriaPrima' => "KAFOL",
                'PrecioUnit' => '49723.34',
            ],
            [
                'slug' => Str::slug('KELATEXMANGANESO'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Foliares")->first()->id,
                'MateriaPrima' => "KELATEXMANGANESO",
                'PrecioUnit' => '28100.275',
            ],
            [
                'slug' => Str::slug('RESIDUOL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Foliares")->first()->id,
                'MateriaPrima' => "RESIDUOL",
                'PrecioUnit' => '113073.1774',
            ],
            [
                'slug' => Str::slug('SURFAGROMICRO'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Foliares")->first()->id,
                'MateriaPrima' => "SURFAGROMICRO",
                'PrecioUnit' => '72960.61183',
            ],
            [
                'slug' => Str::slug('TRIFESOL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Foliares")->first()->id,
                'MateriaPrima' => "TRIFESOL",
                'PrecioUnit' => '103298.7109',
            ],
            [
                'slug' => Str::slug('FERTIGRO'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Foliares")->first()->id,
                'MateriaPrima' => "FERTIGRO",
                'PrecioUnit' => '23157.6107',
            ],

            // Fungicidas
            [
                'slug' => Str::slug('AMISTAR'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Fungicidas")->first()->id,
                'MateriaPrima' => "AMISTAR",
                'PrecioUnit' => '146553.4645',
            ],
            [
                'slug' => Str::slug('CAPSIALIL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Fungicidas")->first()->id,
                'MateriaPrima' => "CAPSIALIL",
                'PrecioUnit' => '172762.4083',
            ],
            [
                'slug' => Str::slug('CORAJE'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Fungicidas")->first()->id,
                'MateriaPrima' => "CORAJE",
                'PrecioUnit' => '612006.409',
            ],
            [
                'slug' => Str::slug('ECOSWING'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Fungicidas")->first()->id,
                'MateriaPrima' => "ECOSWING",
                'PrecioUnit' => '117017.2467',
            ],
            [
                'slug' => Str::slug('FIPRONIL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Fungicidas")->first()->id,
                'MateriaPrima' => "FIPRONIL",
                'PrecioUnit' => '125277.5423',
            ],
            [
                'slug' => Str::slug('FORWIN'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Fungicidas")->first()->id,
                'MateriaPrima' => "FORWIN",
                'PrecioUnit' => '100876.8503',
            ],
            [
                'slug' => Str::slug('FUDIOLAN'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Fungicidas")->first()->id,
                'MateriaPrima' => "FUDIOLAN",
                'PrecioUnit' => '22687.87986',
            ],
            [
                'slug' => Str::slug('MANZATE'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Fungicidas")->first()->id,
                'MateriaPrima' => "MANZATE",
                'PrecioUnit' => '19609.73365',
            ],
            [
                'slug' => Str::slug('MUSACARE'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Fungicidas")->first()->id,
                'MateriaPrima' => "MUSACARE",
                'PrecioUnit' => '51411.19524',
            ],
            [
                'slug' => Str::slug('NATIVO'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Fungicidas")->first()->id,
                'MateriaPrima' => "NATIVO",
                'PrecioUnit' => '94528.47021',
            ],
            [
                'slug' => Str::slug('SERENADE'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Fungicidas")->first()->id,
                'MateriaPrima' => "SERENADE",
                'PrecioUnit' => '42695.08079',
            ],
            [
                'slug' => Str::slug('TASPA'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Fungicidas")->first()->id,
                'MateriaPrima' => "TASPA",
                'PrecioUnit' => '109206.152',
            ],
            [
                'slug' => Str::slug('TIMOREXGOLD'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Fungicidas")->first()->id,
                'MateriaPrima' => "TIMOREXGOLD",
                'PrecioUnit' => '125246.2906',
            ],
            [
                'slug' => Str::slug(' '),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Fungicidas")->first()->id,
                'MateriaPrima' => " ",
                'PrecioUnit' => '217637.3658',
            ],

            // Herbicidas
            [
                'slug' => Str::slug('2.4DEAMINA'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "2.4DEAMINA",
                'PrecioUnit' => '14982.54136',
            ],
            [
                'slug' => Str::slug('ACETOGAN'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "ACETOGAN",
                'PrecioUnit' => '97429.18107',
            ],
            [
                'slug' => Str::slug('AFFINITY400'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "AFFINITY400",
                'PrecioUnit' => '567610.6315',
            ],
            [
                'slug' => Str::slug('AMBOX'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "AMBOX",
                'PrecioUnit' => '52186.27552',
            ],
            [
                'slug' => Str::slug('ARROW'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "ARROW",
                'PrecioUnit' => '48342.50183',
            ],
            [
                'slug' => Str::slug('AURA'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "AURA",
                'PrecioUnit' => '150642.7751',
            ],
            [
                'slug' => Str::slug('BAIKAL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "BAIKAL",
                'PrecioUnit' => '214151.7494',
            ],
            [
                'slug' => Str::slug('BASAGRAN'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "BASAGRAN",
                'PrecioUnit' => '69471.01518',
            ],
            [
                'slug' => Str::slug('BUTACLOR'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "BUTACLOR",
                'PrecioUnit' => '18165.72137',
            ],
            [
                'slug' => Str::slug('CLINCHER'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "CLINCHER",
                'PrecioUnit' => '95972.81012',
            ],
            [
                'slug' => Str::slug('COVERADVANCE'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "COVERADVANCE",
                'PrecioUnit' => '64776.26088',
            ],
            [
                'slug' => Str::slug('DUAL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "DUAL",
                'PrecioUnit' => '88812.5',
            ],
            [
                'slug' => Str::slug('EUROLIGHTNINGBASF'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "EUROLIGHTNINGBASF",
                'PrecioUnit' => '232024.7821',
            ],
            [
                'slug' => Str::slug('FINALE'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "FINALE",
                'PrecioUnit' => '32592.47176',
            ],
            [
                'slug' => Str::slug('GAMIT'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "GAMIT",
                'PrecioUnit' => '111340.5065',
            ],
            [
                'slug' => Str::slug('GESAPRIM'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "GESAPRIM",
                'PrecioUnit' => '54209.94123',
            ],
            [
                'slug' => Str::slug('HEAT'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "HEAT",
                'PrecioUnit' => '691942.1469',
            ],
            [
                'slug' => Str::slug('LINAP'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "LINAP",
                'PrecioUnit' => '34159.53821',
            ],
            [
                'slug' => Str::slug('LOYANT'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "LOYANT",
                'PrecioUnit' => '240533.6479',
            ],
            [
                'slug' => Str::slug('MASSIMO10-6SL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "MASSIMO10-6SL",
                'PrecioUnit' => '75972.344',
            ],
            [
                'slug' => Str::slug('MAYORAL350SL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "MAYORAL350SL",
                'PrecioUnit' => '249397.1319',
            ],
            [
                'slug' => Str::slug('ODYSSEY480EC'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "ODYSSEY480EC",
                'PrecioUnit' => '91037.66254',
            ],
            [
                'slug' => Str::slug('OXYFLUORFEN'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "OXYFLUORFEN",
                'PrecioUnit' => '52508.52261',
            ],
            [
                'slug' => Str::slug('PARAQUAT'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "PARAQUAT",
                'PrecioUnit' => '11406.79013',
            ],
            [
                'slug' => Str::slug('PELICAN500'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "PELICAN500",
                'PrecioUnit' => '215918.5044',
            ],
            [
                'slug' => Str::slug('PERMIT'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "PERMIT",
                'PrecioUnit' => '105661.1705',
            ],
            [
                'slug' => Str::slug('PICLORAN240'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "PICLORAN240",
                'PrecioUnit' => '59044.2994',
            ],
            [
                'slug' => Str::slug('PROPANIL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "PROPANIL",
                'PrecioUnit' => '17114.02874',
            ],
            [
                'slug' => Str::slug('PYRASOSULFURON'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "PYRASOSULFURON",
                'PrecioUnit' => '96419.0703',
            ],
            [
                'slug' => Str::slug('QUINCLORAC'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "QUINCLORAC",
                'PrecioUnit' => '43430.95774',
            ],
            [
                'slug' => Str::slug('RONSTARFLO38'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "RONSTARFLO38",
                'PrecioUnit' => '109775.0296',
            ],
            [
                'slug' => Str::slug('ROUNDUPBRIO'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "ROUNDUPBRIO",
                'PrecioUnit' => '26213.95156',
            ],
            [
                'slug' => Str::slug('SIRIUS'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "SIRIUS",
                'PrecioUnit' => '75008.5',
            ],
            [
                'slug' => Str::slug('STAMM4'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "STAMM4",
                'PrecioUnit' => '37984.53779',
            ],
            [
                'slug' => Str::slug('TORDON'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "TORDON",
                'PrecioUnit' => '31785.92672',
            ],
            [
                'slug' => Str::slug('GAVILAN'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "GAVILAN",
                'PrecioUnit' => '46181.77851',
            ],
            [
                'slug' => Str::slug('RIFIT'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "RIFIT",
                'PrecioUnit' => '43354.36872',
            ],
            [
                'slug' => Str::slug('TRONADOR'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "TRONADOR",
                'PrecioUnit' => '20680.53273',
            ],
            [
                'slug' => Str::slug('EXOCET35EC'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "EXOCET35EC",
                'PrecioUnit' => '88432.88022',
            ],
            [
                'slug' => Str::slug('K-TIONIC'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Herbicidas")->first()->id,
                'MateriaPrima' => "K-TIONIC",
                'PrecioUnit' => '31211.56465',
            ],
            [
                'slug' => Str::slug('CRUICERARROZ'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Insecticidas")->first()->id,
                'MateriaPrima' => "CRUICERARROZ",
                'PrecioUnit' => '171766.6024',
            ],
            [
                'slug' => Str::slug('DACONIL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Insecticidas")->first()->id,
                'MateriaPrima' => "DACONIL",
                'PrecioUnit' => '35179.82331',
            ],
            [
                'slug' => Str::slug('ENGEOS'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Insecticidas")->first()->id,
                'MateriaPrima' => "ENGEOS",
                'PrecioUnit' => '115081.1567',
            ],
            [
                'slug' => Str::slug('EXALT60SC'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Insecticidas")->first()->id,
                'MateriaPrima' => "EXALT60SC",
                'PrecioUnit' => '369710.6508',
            ],
            [
                'slug' => Str::slug('KASUMIN'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Insecticidas")->first()->id,
                'MateriaPrima' => "KASUMIN",
                'PrecioUnit' => '10097.22991',
            ],
            [
                'slug' => Str::slug('NEFASTO'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Insecticidas")->first()->id,
                'MateriaPrima' => "NEFASTO",
                'PrecioUnit' => '233315.5527',
            ],
            [
                'slug' => Str::slug('TRISICLASOL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Insecticidas")->first()->id,
                'MateriaPrima' => "TRISICLASOL",
                'PrecioUnit' => '56291.24412',
            ],
            [
                'slug' => Str::slug('COSMOOIL'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Pegantes")->first()->id,
                'MateriaPrima' => "COSMOOIL",
                'PrecioUnit' => '22825.68596',
            ],
            [
                'slug' => Str::slug('DASH'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Pegantes")->first()->id,
                'MateriaPrima' => "DASH",
                'PrecioUnit' => '48215.43107',
            ],
            [
                'slug' => Str::slug('PORTADOR'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Pegantes")->first()->id,
                'MateriaPrima' => "PORTADOR",
                'PrecioUnit' => '19518.14095',
            ],
            [
                'slug' => Str::slug('SYSCOMET'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Pegantes")->first()->id,
                'MateriaPrima' => "SYSCOMET",
                'PrecioUnit' => '31314.20157',
            ],
            [
                'slug' => Str::slug('SEMILLACLEARFIELIBIS'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Semillas")->first()->id,
                'MateriaPrima' => "SEMILLACLEARFIELIBIS",
                'PrecioUnit' => '251526.0303',
            ],
            [
                'slug' => Str::slug('SEMILLAFEDE-2000SANTAANA'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Semillas")->first()->id,
                'MateriaPrima' => "SEMILLAFEDE-2000SANTAANA",
                'PrecioUnit' => '152555.65',
            ],
            [
                'slug' => Str::slug('SEMILLAFEDE68SANTAANA'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Semillas")->first()->id,
                'MateriaPrima' => "SEMILLAFEDE68SANTAANA",
                'PrecioUnit' => '179327.5105',
            ],
            [
                'slug' => Str::slug('SEMILLAFEDE-70SANTAANA'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Semillas")->first()->id,
                'MateriaPrima' => "SEMILLAFEDE-70SANTAANA",
                'PrecioUnit' => '192929.0023',
            ],
            [
                'slug' => Str::slug('SEMILLAFEDEARROZ2000'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Semillas")->first()->id,
                'MateriaPrima' => "SEMILLAFEDEARROZ2000",
                'PrecioUnit' => '237063.8682',
            ],
            [
                'slug' => Str::slug('SEMILLAFEDEARROZ2020'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Semillas")->first()->id,
                'MateriaPrima' => "SEMILLAFEDEARROZ2020",
                'PrecioUnit' => '238574.1528',
            ],
            [
                'slug' => Str::slug('SEMILLAFEDEARROZGUALANDAY'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Semillas")->first()->id,
                'MateriaPrima' => "SEMILLAFEDEARROZGUALANDAY",
                'PrecioUnit' => '238221.515',
            ],
            [
                'slug' => Str::slug('SEMILLAORIZICA1'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Semillas")->first()->id,
                'MateriaPrima' => "SEMILLAORIZICA1",
                'PrecioUnit' => '220255.8171',
            ],
            [
                'slug' => Str::slug('SEMILLAPANORAMA394'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Semillas")->first()->id,
                'MateriaPrima' => "SEMILLAPANORAMA394",
                'PrecioUnit' => '222687.4608',
            ],
            [
                'slug' => Str::slug('SEMILLAFEDEARROZ67'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Semillas")->first()->id,
                'MateriaPrima' => "SEMILLAFEDEARROZ67",
                'PrecioUnit' => '201858.1453',
            ],
            [
                'slug' => Str::slug('SEMILLATHAILANDIAOROJPG'),  // Slug
                'GrupoMPrima_id' => Grupo_MateriaPrima::where("GrupoMateriaPrima", "Semillas")->first()->id,
                'MateriaPrima' => "SEMILLATHAILANDIAOROJPG",
                'PrecioUnit' => '162400',
            ],
        ];
        // Crear Registros
        DB::table('materia_primas')->insert($materiaPrima);
    }
}
