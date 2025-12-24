<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnidadMedida extends Model
{
    use HasFactory;

    protected $table = 'unidad_medidas';

    protected $fillable = [
        'id',
        'slug',
        'UnidadMedida',
    ];


    public function CumplidoOrdenServicioDetalle(): HasMany
    {
        return $this->HasMany(CumplidoOrdenServicioDetalle::class, 'UnidadMedida_id', 'id');
    }

    public function CumpLaborcampo(): HasMany
    {
        return $this->hasMany(cumplido_laborcampo::class, 'unidadmedida_id', 'id');
    }

    public static function optionsUnidadMedida(?int $UnidadMedida_id = null, ?array $optionsUnidadMedida = null)
    {
        $query = static::select(['id', 'UnidadMedida']);

        if (!is_null($optionsUnidadMedida)) {
            $query->where('id', $optionsUnidadMedida);
            if ($data = $query->first()) {
                return [
                    'id' => $data->id,
                    'label' => $data->UnidadMedida,
                ];
            }
        } elseif (!is_null($UnidadMedida_id)) {
            $query->where('id', $UnidadMedida_id);
            if ($data = $query->first()) {
                return [
                    'id' => $data->id,
                    'label' => $data->UnidadMedida,
                ];
            }
        }

        return $query->get()->map(fn($data) => [
            'id' => $data->id,
            'label' => $data->UnidadMedida,
        ]);
    }
}
