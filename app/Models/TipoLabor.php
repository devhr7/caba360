<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TipoLabor extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'tipo_labors';

    // Campos
    protected $fillable = [
        'slug',
        'TipoLabor',
    ];

    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($data) {
            $data->slug = Str::slug($data->TipoLabor);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($data) {
            $data->slug = Str::slug($data->TipoLabor);
        });
    }

    // Funcion Para Reconozca el Slug dentro de la ruta
    public function getRouteKey(): mixed
    {
        return $this->slug;
    }

    public function Labor(): HasMany
    {
        return $this->hasMany(Labor::class, 'TipoLabor_id', 'id');
    }
    public function CumplidoMaquinaria(): HasMany
    {
        return $this->hasMany(CumplidoMaquinaria::class, 'tipolabor_id', 'id');
    }

    public static function optionsTipoLabor(?int $TipolaborId = null, ?array $TiposlaboresId = null)
    {
        $query = static::select(['id', 'TipoLabor']);

        if ($TipolaborId) {
            $query->where('id', $TipolaborId);

            if ($datos = $query->first()) {
                return [
                    'id' => $datos->id,
                    'label' => $datos->TipoLabor,
                ];
            }
        } else {
            if ($TiposlaboresId) {
                $query->whereIn('id', $TiposlaboresId);
            }
        }

        return $query->get()->map(fn($datos) => [
            'id' => $datos->id,
            'label' => $datos->TipoLabor,

        ]);
    }
}
