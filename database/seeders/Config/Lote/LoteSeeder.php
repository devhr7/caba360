<?php

namespace Database\Seeders\Config\Lote;

use App\Models\Distrito;
use App\Models\Finca;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Registro
        $Lote = [
            // Santa Ana
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('5A1'))), 'hect' => '6', 'slug' => Str::slug('Meseta Santa Ana 5A1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('5A2'))), 'hect' => '12', 'slug' => Str::slug('Meseta Santa Ana 5A2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('5B1'))), 'hect' => '9', 'slug' => Str::slug('Meseta Santa Ana 5B1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('5B2'))), 'hect' => '12', 'slug' => Str::slug('Meseta Santa Ana 5B2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('2A1'))), 'hect' => '8', 'slug' => Str::slug('Meseta Santa Ana 2A1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('2A2'))), 'hect' => '6', 'slug' => Str::slug('Meseta Santa Ana 2A2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('2B1'))), 'hect' => '7', 'slug' => Str::slug('Meseta Santa Ana 2B1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('2B2'))), 'hect' => '6', 'slug' => Str::slug('Meseta Santa Ana 2B2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('4A'))), 'hect' => '11', 'slug' => Str::slug('Meseta Santa Ana 4A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('4B'))), 'hect' => '14', 'slug' => Str::slug('Meseta Santa Ana 4B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('3A1'))), 'hect' => '11', 'slug' => Str::slug('Meseta Santa Ana 3A1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('3A2'))), 'hect' => '9', 'slug' => Str::slug('Meseta Santa Ana 3A2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('3B'))), 'hect' => '16', 'slug' => Str::slug('Meseta Santa Ana 3B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('6B1'))), 'hect' => '6', 'slug' => Str::slug('Meseta Santa Ana 6B1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('6B2'))), 'hect' => '8', 'slug' => Str::slug('Meseta Santa Ana 6B2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('6A1'))), 'hect' => '8', 'slug' => Str::slug('Meseta Santa Ana 6A1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('6A2'))), 'hect' => '12', 'slug' => Str::slug('Meseta Santa Ana 6A2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('7A'))), 'hect' => '10', 'slug' => Str::slug('Meseta Santa Ana 7A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('7B'))), 'hect' => '8', 'slug' => Str::slug('Meseta Santa Ana 7B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('7C'))), 'hect' => '17', 'slug' => Str::slug('Meseta Santa Ana 7C')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('8A'))), 'hect' => '10', 'slug' => Str::slug('Meseta Santa Ana 8A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('8B'))), 'hect' => '16', 'slug' => Str::slug('Meseta Santa Ana 8B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('8C'))), 'hect' => '17', 'slug' => Str::slug('Meseta Santa Ana 8C')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('9A'))), 'hect' => '12', 'slug' => Str::slug('Meseta Santa Ana 9A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('9B'))), 'hect' => '11', 'slug' => Str::slug('Meseta Santa Ana 9B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('9C'))), 'hect' => '12', 'slug' => Str::slug('Meseta Santa Ana 9C')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('9D'))), 'hect' => '14', 'slug' => Str::slug('Meseta Santa Ana 9D')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('19'))), 'hect' => '14', 'slug' => Str::slug('Meseta Santa Ana 19')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('20A'))), 'hect' => '14', 'slug' => Str::slug('Meseta Santa Ana 20A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Santa Ana")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('20B'))), 'hect' => '14', 'slug' => Str::slug('Meseta Santa Ana 20B')],

            //Arrayan
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Arrayan")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('BAYOS 1'))), 'hect' => '15', 'slug' => Str::slug('Meseta Arrayan BAYOS 1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Arrayan")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('BAYOS 2'))), 'hect' => '10', 'slug' => Str::slug('Meseta Arrayan BAYOS 2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Arrayan")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('BAYOS 4'))), 'hect' => '8', 'slug' => Str::slug('Meseta Arrayan BAYOS 4')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Arrayan")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('BAYOS 5'))), 'hect' => '8', 'slug' => Str::slug('Meseta Arrayan BAYOS 5')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Arrayan")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('PALMAS 1'))), 'hect' => '14', 'slug' => Str::slug('Meseta Arrayan PALMAS 1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Arrayan")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('PALMAS 2'))), 'hect' => '12', 'slug' => Str::slug('Meseta Arrayan PALMAS 2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Arrayan")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('PALMAS 3'))), 'hect' => '12', 'slug' => Str::slug('Meseta Arrayan PALMAS 3')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Arrayan")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('PALMAS 4'))), 'hect' => '16', 'slug' => Str::slug('Meseta Arrayan PALMAS 4')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Arrayan")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('POZO 1'))), 'hect' => '20', 'slug' => Str::slug('Meseta Arrayan POZO 1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Arrayan")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('POZO 2'))), 'hect' => '15', 'slug' => Str::slug('Meseta Arrayan POZO 2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id, 'finca_id' => Finca::where("finca", "Arrayan")->first()->id, 'lote' => Str::trim(Str::squish(Str::upper('POZO 3'))), 'hect' => '15', 'slug' => Str::slug('Meseta Arrayan POZO 3')],

            // Barbona
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('8'))),'hect'=>'17','slug'=>Str::slug('Meseta Barbona 8')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('9'))),'hect'=>'16','slug'=>Str::slug('Meseta Barbona 9')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('10'))),'hect'=>'17','slug'=>Str::slug('Meseta Barbona 10')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('11'))),'hect'=>'15','slug'=>Str::slug('Meseta Barbona 11')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('12'))),'hect'=>'14','slug'=>Str::slug('Meseta Barbona 12')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('13A'))),'hect'=>'11','slug'=>Str::slug('Meseta Barbona 13A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('13B'))),'hect'=>'10','slug'=>Str::slug('Meseta Barbona 13B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('14A'))),'hect'=>'13','slug'=>Str::slug('Meseta Barbona 14A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('14B'))),'hect'=>'13','slug'=>Str::slug('Meseta Barbona 14B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('15A'))),'hect'=>'8','slug'=>Str::slug('Meseta Barbona 15A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('15B'))),'hect'=>'12','slug'=>Str::slug('Meseta Barbona 15B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('16A'))),'hect'=>'15','slug'=>Str::slug('Meseta Barbona 16A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('16B'))),'hect'=>'12','slug'=>Str::slug('Meseta Barbona 16B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('7A'))),'hect'=>'8','slug'=>Str::slug('Meseta Barbona 7A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('7B'))),'hect'=>'7','slug'=>Str::slug('Meseta Barbona 7B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('BIAMPU 1A'))),'hect'=>'5','slug'=>Str::slug('Meseta Barbona BIAMPU 1A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('BIAMPU 1B'))),'hect'=>'7','slug'=>Str::slug('Meseta Barbona BIAMPU 1B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('BIAMPU 2A'))),'hect'=>'10','slug'=>Str::slug('Meseta Barbona BIAMPU 2A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('BIAMPU 3A'))),'hect'=>'11','slug'=>Str::slug('Meseta Barbona BIAMPU 3A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('BIAMPU 4AB'))),'hect'=>'14','slug'=>Str::slug('Meseta Barbona BIAMPU 4AB')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('BIAMPU 5AB'))),'hect'=>'16','slug'=>Str::slug('Meseta Barbona BIAMPU 5AB')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('TELEFONO 1'))),'hect'=>'9','slug'=>Str::slug('Meseta Barbona TELEFONO 1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('TELEFONO 2'))),'hect'=>'12','slug'=>Str::slug('Meseta Barbona TELEFONO 2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('TELEFONO 3'))),'hect'=>'12','slug'=>Str::slug('Meseta Barbona TELEFONO 3')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('TELEFONO 4'))),'hect'=>'12','slug'=>Str::slug('Meseta Barbona TELEFONO 4')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('ZUNGA 1'))),'hect'=>'16','slug'=>Str::slug('Meseta Barbona ZUNGA 1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('ZUNGA 2A3B'))),'hect'=>'14','slug'=>Str::slug('Meseta Barbona ZUNGA 2A3B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('ZUNGA 2B2C'))),'hect'=>'11','slug'=>Str::slug('Meseta Barbona ZUNGA 2B2C')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Barbona")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('ZUNGA 3A'))),'hect'=>'11','slug'=>Str::slug('Meseta Barbona ZUNGA 3A')],

            // Bustamante
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Bustamante")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('54'))),'hect'=>'16','slug'=>Str::slug('Meseta Bustamante 54')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Bustamante")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('55'))),'hect'=>'18','slug'=>Str::slug('Meseta Bustamante 55')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Bustamante")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('56'))),'hect'=>'14','slug'=>Str::slug('Meseta Bustamante 56')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Bustamante")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('57'))),'hect'=>'13','slug'=>Str::slug('Meseta Bustamante 57')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Bustamante")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('62'))),'hect'=>'20','slug'=>Str::slug('Meseta Bustamante 62')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Bustamante")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('58A'))),'hect'=>'7','slug'=>Str::slug('Meseta Bustamante 58A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Bustamante")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('58B'))),'hect'=>'10','slug'=>Str::slug('Meseta Bustamante 58B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Bustamante")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('60A'))),'hect'=>'12','slug'=>Str::slug('Meseta Bustamante 60A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Bustamante")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('60B'))),'hect'=>'12','slug'=>Str::slug('Meseta Bustamante 60B')],

            // Cabras
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Cabras")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('1'))),'hect'=>'17','slug'=>Str::slug('Meseta Cabras 1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Cabras")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('2'))),'hect'=>'17','slug'=>Str::slug('Meseta Cabras 2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Cabras")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('3'))),'hect'=>'14','slug'=>Str::slug('Meseta Cabras 3')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Cabras")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('4'))),'hect'=>'12','slug'=>Str::slug('Meseta Cabras 4')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Cabras")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('5'))),'hect'=>'12','slug'=>Str::slug('Meseta Cabras 5')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Cabras")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('6'))),'hect'=>'12','slug'=>Str::slug('Meseta Cabras 6')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Cabras")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('7A'))),'hect'=>'8','slug'=>Str::slug('Meseta Cabras 7A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Cabras")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('7B'))),'hect'=>'7','slug'=>Str::slug('Meseta Cabras 7B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Cabras")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('8A'))),'hect'=>'7','slug'=>Str::slug('Meseta Cabras 8A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Cabras")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('8B'))),'hect'=>'5','slug'=>Str::slug('Meseta Cabras 8B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Cabras")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('CAMELIAS 1'))),'hect' => '7', 'slug'=>Str::slug('Meseta Cabras CAMELIAS 1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Cabras")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('CAMELIAS 2'))),'hect' => '7', 'slug'=>Str::slug('Meseta Cabras CAMELIAS 2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Cabras")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('MANGAS COR'))),'hect' => '3', 'slug'=>Str::slug('Meseta Cabras MANGAS COR')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Cabras")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('MANGAS GAL'))),'hect' => '9', 'slug'=>Str::slug('Meseta Cabras MANGAS GAL')],

            // Coburgos
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('1'))),'hect'=>'6','slug'=>Str::slug('Meseta Coburgos 1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('5'))),'hect'=>'10','slug'=>Str::slug('Meseta Coburgos 5')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('6'))),'hect'=>'11','slug'=>Str::slug('Meseta Coburgos 6')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('7'))),'hect'=>'16','slug'=>Str::slug('Meseta Coburgos 7')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('8'))),'hect'=>'10','slug'=>Str::slug('Meseta Coburgos 8')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('9'))),'hect'=>'10','slug'=>Str::slug('Meseta Coburgos 9')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('10'))),'hect'=>'9','slug'=>Str::slug('Meseta Coburgos 10')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('11'))),'hect'=>'10','slug'=>Str::slug('Meseta Coburgos 11')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('12'))),'hect'=>'9','slug'=>Str::slug('Meseta Coburgos 12')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('14'))),'hect'=>'10','slug'=>Str::slug('Meseta Coburgos 14')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('15'))),'hect'=>'8','slug'=>Str::slug('Meseta Coburgos 15')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('13A'))),'hect'=>'8','slug'=>Str::slug('Meseta Coburgos 13A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('13B'))),'hect'=>'7','slug'=>Str::slug('Meseta Coburgos 13B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('2A'))),'hect'=>'13','slug'=>Str::slug('Meseta Coburgos 2A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('2B'))),'hect'=>'4','slug'=>Str::slug('Meseta Coburgos 2B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('3A'))),'hect'=>'3','slug'=>Str::slug('Meseta Coburgos 3A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('3B'))),'hect'=>'3','slug'=>Str::slug('Meseta Coburgos 3B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('3C'))),'hect'=>'8','slug'=>Str::slug('Meseta Coburgos 3C')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('4A'))),'hect'=>'9','slug'=>Str::slug('Meseta Coburgos 4A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Coburgos")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('4B'))),'hect'=>'6','slug'=>Str::slug('Meseta Coburgos 4B')],

            // Colombia
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('1'))),'hect'=>'5','slug'=>Str::slug('Meseta Colombia 1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('2'))),'hect'=>'15','slug'=>Str::slug('Meseta Colombia 2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('11'))),'hect'=>'6','slug'=>Str::slug('Meseta Colombia 11')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('12'))),'hect'=>'12','slug'=>Str::slug('Meseta Colombia 12')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('13'))),'hect'=>'7','slug'=>Str::slug('Meseta Colombia 13')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('14'))),'hect'=>'7','slug'=>Str::slug('Meseta Colombia 14')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('10AB'))),'hect'=>'21','slug'=>Str::slug('Meseta Colombia 10AB')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('10C'))),'hect'=>'7','slug'=>Str::slug('Meseta Colombia 10C')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('3A'))),'hect'=>'10','slug'=>Str::slug('Meseta Colombia 3A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('3B'))),'hect'=>'13','slug'=>Str::slug('Meseta Colombia 3B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('4A'))),'hect'=>'8','slug'=>Str::slug('Meseta Colombia 4A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('4B'))),'hect'=>'15','slug'=>Str::slug('Meseta Colombia 4B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('5A'))),'hect'=>'22','slug'=>Str::slug('Meseta Colombia 5A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('5B'))),'hect'=>'10','slug'=>Str::slug('Meseta Colombia 5B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('5C'))),'hect'=>'19','slug'=>Str::slug('Meseta Colombia 5C')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('6A'))),'hect'=>'10','slug'=>Str::slug('Meseta Colombia 6A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('6B'))),'hect'=>'11','slug'=>Str::slug('Meseta Colombia 6B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('6C'))),'hect'=>'10','slug'=>Str::slug('Meseta Colombia 6C')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('7A1'))),'hect'=>'7','slug'=>Str::slug('Meseta Colombia 7A1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('7A2'))),'hect'=>'9','slug'=>Str::slug('Meseta Colombia 7A2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('7B'))),'hect'=>'11','slug'=>Str::slug('Meseta Colombia 7B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('8A'))),'hect'=>'13','slug'=>Str::slug('Meseta Colombia 8A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('8B'))),'hect'=>'9','slug'=>Str::slug('Meseta Colombia 8B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('8C'))),'hect'=>'14','slug'=>Str::slug('Meseta Colombia 8C')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('9B'))),'hect'=>'12','slug'=>Str::slug('Meseta Colombia 9B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('9C'))),'hect'=>'4','slug'=>Str::slug('Meseta Colombia 9C')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('BOMBA 1'))),'hect'=>'9','slug'=>Str::slug('Meseta Colombia BOMBA 1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('BOMBA 2'))),'hect'=>'9','slug'=>Str::slug('Meseta Colombia BOMBA 2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('BOMBA 3'))),'hect'=>'10','slug'=>Str::slug('Meseta Colombia BOMBA 3')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('BOMBA 4'))),'hect'=>'9','slug'=>Str::slug('Meseta Colombia BOMBA 4')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('BOMBA 5'))),'hect'=>'8','slug'=>Str::slug('Meseta Colombia BOMBA 5')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('BOMBA 6'))),'hect'=>'8','slug'=>Str::slug('Meseta Colombia BOMBA 6')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('ESPINOS 1'))),'hect'=>'8','slug'=>Str::slug('Meseta Colombia ESPINOS 1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Colombia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('ESPINOS 2'))),'hect'=>'3','slug'=>Str::slug('Meseta Colombia ESPINOS 2')],


            // Gascoña
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Gasconia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('2'))),'hect'=>'14','slug'=>Str::slug('Meseta Gascona 2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Gasconia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('3'))),'hect'=>'6','slug'=>Str::slug('Meseta Gascona 3')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Gasconia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('4'))),'hect'=>'12','slug'=>Str::slug('Meseta Gascona 4')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Gasconia")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('5'))),'hect'=>'9','slug'=>Str::slug('Meseta Gascona 5')],

            // Matica
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","La Matica")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('23'))),'hect'=>'26','slug'=>Str::slug('Meseta La Matica 23')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","La Matica")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('27'))),'hect'=>'28','slug'=>Str::slug('Meseta La Matica 27')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","La Matica")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('28'))),'hect'=>'16','slug'=>Str::slug('Meseta La Matica 28')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","La Matica")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('13AB'))),'hect'=>'17','slug'=>Str::slug('Meseta La Matica 13AB')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","La Matica")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('13C'))),'hect'=>'14','slug'=>Str::slug('Meseta La Matica 13C')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","La Matica")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('17A'))),'hect'=>'11','slug'=>Str::slug('Meseta La Matica 17A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","La Matica")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('17B'))),'hect'=>'14','slug'=>Str::slug('Meseta La Matica 17B')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","La Matica")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('17C'))),'hect'=>'10','slug'=>Str::slug('Meseta La Matica 17C')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","La Matica")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('22A'))),'hect'=>'13','slug'=>Str::slug('Meseta La Matica 22A')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","La Matica")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('22B'))),'hect'=>'19','slug'=>Str::slug('Meseta La Matica 22B')],

            // Recreo
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Recreo")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('1'))),'hect'=>'9','slug'=>Str::slug('Meseta Recreo 1')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Recreo")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('2'))),'hect'=>'11','slug'=>Str::slug('Meseta Recreo 2')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Recreo")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('3'))),'hect'=>'6','slug'=>Str::slug('Meseta Recreo 3')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Recreo")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('4'))),'hect'=>'4','slug'=>Str::slug('Meseta Recreo 4')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Recreo")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('5'))),'hect'=>'5','slug'=>Str::slug('Meseta Recreo 5')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Recreo")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('6'))),'hect'=>'20','slug'=>Str::slug('Meseta Recreo 6')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Recreo")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('CARMEN'))),'hect'=>'10','slug'=>Str::slug('Meseta Recreo CARMEN')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Recreo")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('CARRETERA'))),'hect'=>'14','slug'=>Str::slug('Meseta Recreo CARRETERA')],
            ['distrito_id' => Distrito::where("distrito", "Meseta")->first()->id,'finca_id'=>Finca::where("finca","Recreo")->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('MONEDA'))),'hect'=>'1','slug'=>Str::slug('Meseta Recreo MONEDA')],

            // Alsacia
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'Alsacia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('1'))),'hect'=>'4','slug'=>Str::slug('Norte Alsacia 1')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'Alsacia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('2'))),'hect'=>'10','slug'=>Str::slug('Norte Alsacia 2')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'Alsacia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('3'))),'hect'=>'10','slug'=>Str::slug('Norte Alsacia 3')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'Alsacia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('4'))),'hect'=>'10','slug'=>Str::slug('Norte Alsacia 4')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'Alsacia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('5'))),'hect'=>'10','slug'=>Str::slug('Norte Alsacia 5')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'Alsacia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('9'))),'hect'=>'9','slug'=>Str::slug('Norte Alsacia 9')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'Alsacia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('10'))),'hect'=>'5','slug'=>Str::slug('Norte Alsacia 10')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'Alsacia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('11'))),'hect'=>'8','slug'=>Str::slug('Norte Alsacia 11')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'Alsacia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('678'))),'hect'=>'20','slug'=>Str::slug('Norte Alsacia 678')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'Alsacia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('DINDAL 1'))),'hect'=>'9','slug'=>Str::slug('Norte Alsacia DINDAL 1')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'Alsacia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('DINDAL 2'))),'hect'=>'9','slug'=>Str::slug('Norte Alsacia DINDAL 2')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'Alsacia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('DINDAL 3'))),'hect'=>'9','slug'=>Str::slug('Norte Alsacia DINDAL 3')],

            // La Italia
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'La Italia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('1'))),'hect'=>'9','slug'=>Str::slug('Norte La Italia 1')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'La Italia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('2'))),'hect'=>'10','slug'=>Str::slug('Norte La Italia 2')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'La Italia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('4'))),'hect'=>'6','slug'=>Str::slug('Norte La Italia 4')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'La Italia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('7'))),'hect'=>'10','slug'=>Str::slug('Norte La Italia 7')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'La Italia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('8'))),'hect'=>'9','slug'=>Str::slug('Norte La Italia 8')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'La Italia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('9'))),'hect'=>'4','slug'=>Str::slug('Norte La Italia 9')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'La Italia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('10'))),'hect'=>'4','slug'=>Str::slug('Norte La Italia 10')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'La Italia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('11'))),'hect'=>'5','slug'=>Str::slug('Norte La Italia 11')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'La Italia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('12'))),'hect'=>'6','slug'=>Str::slug('Norte La Italia 12')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'La Italia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('13'))),'hect'=>'6','slug'=>Str::slug('Norte La Italia 13')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'La Italia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('14'))),'hect'=>'10','slug'=>Str::slug('Norte La Italia 14')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'La Italia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('3AB'))),'hect'=>'6','slug'=>Str::slug('Norte La Italia 3AB')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'La Italia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('5A6A'))),'hect'=>'16','slug'=>Str::slug('Norte La Italia 5A6A')],
            ['distrito_id' => Distrito::where("distrito", "Norte")->first()->id,'finca_id'=>Finca::where("finca",'La Italia')->first()->id,'lote'=>Str::trim(Str::squish(Str::upper('5B6B'))),'hect'=>'9','slug'=>Str::slug('Norte La Italia 5B6B')],


        ];
        // Crear Registros
        DB::table('lotes')->insert($Lote);
    }
}
