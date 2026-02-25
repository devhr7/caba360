<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClasificacionCentroCosto extends Model
{
    use HasFactory;

    protected $table = 'clasificacion_centro_costos';

    protected $fillable = [
        'id',
        'slug',
        'ClaseCentroCosto',
    ];


    public function ClasificacionCentroCosto(): HasMany
    {
        return $this->HasMany(ClasificacionCentroCosto::class, 'TipoCentroCosto_id', 'id');
    }

    public static function optionsClaseCentroCosto(?int $ClaseCentroCosto_id = null, ?array $optionsClaseCentroCosto = null)
    {
        $query = static::select(['id', 'ClaseCentroCosto']);


        if (!is_null($optionsClaseCentroCosto)) {
            $query->whereIn('id', $optionsClaseCentroCosto);
        } elseif (!is_null($ClaseCentroCosto_id)) {
            $query->where('id', $ClaseCentroCosto_id);
            if ($data = $query->first()) {
                return [
                    'id' => $data->id,
                    'label' => $data->ClaseCentroCosto,
                ];
            }
        }

        return $query->get()->map(fn($data) => [
            'id' => $data->id,
            'label' => $data->ClaseCentroCosto,
        ]);
    }
}
