<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TipoGasto extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'tipo_gastos';

    // Campos
    protected $fillable = [
        'slug',
        'codigo',
        'nombre',
        'gasto_id',
    ];


    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($data) {
            $data->slug = Str::slug($data->TipoGasto);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($data) {
            $data->slug = Str::slug($data->TipoGasto);
        });
    }

    // Funcion Para Reconozca el Slug dentro de la ruta
    public function getRouteKey(): mixed
    {
        return $this->slug;
    }


    // Relacion con Gasto
    public function gasto(): BelongsTo
    {
        return $this->belongsTo(gasto::class);
    }

    public static function optionsTipoGasto(?int $tipogasto_id = null, ?int $gasto_id = null)
    {
        $query = static::select(['id', 'codigo', 'nombre']);
        if ($gasto_id) {
            $query->where('gasto_id', $gasto_id);
        }

        if ($tipogasto_id) {
            $query->where('id', $tipogasto_id);
            $data = $query->first();
            if ($tipogasto_id && $data) {
                return [
                    'id' => $data->id,
                    'label' => $data->nombre,
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
