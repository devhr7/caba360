<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Potrero extends Model
{
    //
    protected $table = 'potreros';

    protected $fillable = [
        'finca_id',
        'nombre',
    ];


    public function finca():BelongsTo
    {
        return $this->belongsTo(Finca::class, 'finca_id');
    }

    public function CumpOrdenServicioDets(): HasMany
    {
        return $this->hasMany(CumplidoOrdenServicioDetalle::class, 'potrero_id', 'id');
    }

public static function optionsPotrero(?int $fincaId = null, ?int $potreroId = null)
{
    $query = static::select(['id', 'nombre', 'finca_id']);

    if ($fincaId) {
        $query->where('finca_id', $fincaId);
    }

    if ($potreroId) {
        $query->where('id', $potreroId);
        $potrero = $query->first();
        if ($potreroId && $potrero) {
            return [
                'id' => $potrero->id,
                'label' => $potrero->nombre,

            ];
        }
    }

    return $query->get()
        ->map(function ($data) {
            return [
                'id' => $data->id,
                'label' => $data->nombre,

            ];
        });
}
}
