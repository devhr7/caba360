<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TipoCultivo extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'tipo_cultivos';

    // Campos
    protected $fillable = [
        'slug',
        'TipoCultivo',
    ];

    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($data) {
            $data->slug = Str::slug($data->TipoCultivo);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($data) {
            $data->slug = Str::slug($data->TipoCultivo);
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
        return $this->hasMany(RegistroLotes::class, 'TipoCultivo_id', 'id');
    }


    public static function optionsTipoCultivo(?int $TipoCultivoId = null)
    {
        $query = static::select(['id', 'TipoCultivo','slug']);
        if ($TipoCultivoId) {
            $query->where('id', $TipoCultivoId);
            $TipoCultivo = $query->first();
            if ($TipoCultivoId ) {
                return [
                    'id' => $TipoCultivo->id,
                    'label' => $TipoCultivo->TipoCultivo,
                ];
            }
        }
        return $query->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'label' => $data->TipoCultivo,
                ];
            });
    }
}
