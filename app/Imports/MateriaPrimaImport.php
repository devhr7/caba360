<?php

namespace App\Imports;

use App\Models\MateriaPrima;
use Maatwebsite\Excel\Concerns\ToModel;

class MateriaPrimaImport implements ToModel
{
    public function model(array $row)
    {
        $materiaPrima = MateriaPrima::where('slug', $row[0])->first();
        if ($materiaPrima && is_numeric($row[2])) {
            $materiaPrima->PrecioUnit = $row[2];
            $materiaPrima->save();
        }
    }
}
