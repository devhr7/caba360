<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoCumplido extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'tipo_cumplidos';

    // Campos
    protected $fillable = [
        'slug',
        'TipoCumplido',
    ];

    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($data) {
            $data->slug = Str::slug($data->TipoCumplido);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($data) {
            $data->slug = Str::slug($data->TipoCumplido);
        });
    }

    // Funcion Para Reconozca el Slug dentro de la ruta
    public function getRouteKey(): mixed
    {
        return $this->slug;
    }

    public function Labor(): HasMany
    {
        return $this->hasMany(Labor::class, 'TipoCumplido_id', 'id');
    }

    // optionsTipoCumplido

    public static function optionsTipoCumplido(?int $TipoCumplidoId = null, ?array $TiposCumplidosId = null)
    {
        $query = static::select(['id', 'TipoCumplido']);

        if ($TipoCumplidoId) {
            $query->where('id', $TipoCumplidoId);

            if ($datos = $query->first()) {
                return [
                    'id' => $datos->id,
                    'label' => $datos->TipoCumplido,
                ];
            }
        } else {
            if ($TiposCumplidosId) {
                $query->whereIn('id', $TiposCumplidosId);
            }
        }

        return $query->get()->map(fn($datos) => [
            'id' => $datos->id,
            'label' => $datos->TipoCumplido,
        ]);
    }
}
