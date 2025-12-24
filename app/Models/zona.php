<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class zona extends Model
{
    //

    protected $table = 'zonas';

    // Campos
    protected $fillable = [
        'id',
        'zona',
        'CentroCosto',
    ];

    public function Finca(): HasMany
    {
        return $this->hasMany(Finca::class, 'zona_id', 'id');
    }

    public static function optionsZona(?int $ZonaId = null)
    {
        $query = static::select(['id', 'zona']);

        if ($ZonaId) {
            $query->where('id', $ZonaId);
            $data = $query->first();
            if ($data) {
                return [
                    'id' => $data->id,
                    'label' => $data->zona,
                ];
            }
        }

        return $query->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'label' => $data->zona,
                ];
            });
    }
}
