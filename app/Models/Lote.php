<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Lote extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'lotes';

    // Campos
    protected $fillable = [
        'slug',
        'lote',
        'hect',
        'finca_id',
        'distrito_id',

    ];

    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($data) {
            $data->slug = Str::slug($data->distrito->distrito . ' ' . $data->finca->finca . " " . $data->lote);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($data) {
            $data->slug = Str::slug($data->distrito->distrito . ' ' . $data->finca->finca . " " . $data->lote);
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
    // Lote
    public function RegLote(): HasMany
    {
        return $this->hasMany(RegistroLotes::class, 'lote_id', 'id');
    }
    public function CumplidoMaquinaria(): HasMany
    {
        return $this->hasMany(CumplidoMaquinaria::class, 'labor_id', 'id');
    }

    public function CumplidoOrdenServicioDetalle(): HasMany
    {
        return $this->HasMany(CumplidoOrdenServicioDetalle::class, 'Lote_id', 'id');
    }

    /**
     * Relaciones Uno a muchos Invertido
     */
    // Distrito
    public function Distrito(): BelongsTo
    {
        return $this->belongsTo(Distrito::class, 'distrito_id', 'id');
    }
    // Finca
    public function Finca(): BelongsTo
    {
        return $this->belongsTo(Finca::class, 'finca_id', 'id');
    }





    public static function optionsLote(?int $fincaId = null, ?int $LoteId = null)
    {
        $query = static::select(['id', 'lote', 'hect', 'finca_id', 'distrito_id', 'slug']);

        if ($fincaId) {
            $query->where('finca_id', $fincaId);
        }

        if ($LoteId) {
            $query->where('id', $LoteId);
            $Lote = $query->first();
            if ($LoteId && $Lote) {
                return [
                    'id' => $Lote->id,
                    'label' => $Lote->lote,
                    'finca_id' => $Lote->distrito_id,
                    'distrito_id' => $Lote->distrito_id,
                ];
            }
        }

        return $query->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'label' => $data->lote,
                    'finca_id' => $data->distrito_id,
                    'distrito_id' => $data->distrito_id,
                ];
            });
    }
}
