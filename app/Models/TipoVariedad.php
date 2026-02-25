<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TipoVariedad extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'tipo_variedads';

    // Campos
    protected $fillable = [
        'slug',
        'TipoVariedad',
    ];

    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($data) {
            $data->slug = Str::slug($data->TipoVariedad);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($data) {
            $data->slug = Str::slug($data->TipoVariedad);
        });
    }

    // Funcion Para Reconozca el Slug dentro de la ruta
    public function getRouteKey(): mixed
    {
        return $this->slug;
    }

     /**
     * Relaciones Uno a Muchos
     */
    // Registro Lotes
    public function RegLote(): HasMany
    {
        return $this->hasMany(RegistroLotes::class, 'TipoVariedad_id', 'id');
    }

    public static function optionsTipoVariedad(?int $TipoVariedadId = null)
    {
        $query = static::select(['id', 'TipoVariedad','slug']);
        if ($TipoVariedadId) {
            $query->where('id', $TipoVariedadId);
            $TipoVariedad = $query->first();
            if ($TipoVariedadId ) {
                return [
                    'id' => $TipoVariedad->id,
                    'label' => $TipoVariedad->TipoVariedad,
                ];
            }
        }
        return $query->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'label' => $data->TipoVariedad,
                ];
            });
    }
}
