<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;


class Finca extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'fincas';

    // Campos
    protected $fillable = [
        'distrito_id',
        'finca',
        'metahect',
        'slug',
        'zona_id',
    ];

    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($finca) {
            $finca->slug = Str::slug($finca->finca);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($finca) {
            $finca->slug = Str::slug($finca->finca);
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
    public function Lote(): HasMany
    {
        return $this->hasMany(Lote::class, 'finca_id', 'id');
    }

    // Reg Lote
    public function RegLote(): HasMany
    {
        return $this->hasMany(RegistroLotes::class, 'finca_id', 'id');
    }

    public function CumplidoMaquinaria(): HasMany
    {
        return $this->hasMany(CumplidoMaquinaria::class, 'finca_id', 'id');
    }


    public function CumplidoOrdenServicioDetalle(): HasMany
    {
        return $this->hasMany(CumplidoOrdenServicioDetalle::class, 'finca_id', 'id');
    }

    // Lote
    public function CumpLaborcampo(): HasMany
    {
        return $this->hasMany(cumplido_laborcampo::class, 'finca_id', 'id');
    }


    public function ppt_detalle_costo(): HasMany
    {
        return $this->hasMany(ppt_detalle_costo::class, 'finca_id', 'id');
    }

    /**
     * Relaciones Uno a muchos Invertido
     */
    // Distrito
    public function Distrito(): BelongsTo
    {
        return $this->belongsTo(Distrito::class, 'distrito_id', 'id');
    }


    public function zona(): BelongsTo
    {
        return $this->belongsTo(zona::class, 'zona_id', 'id');
    }

    public function potrero(): HasMany
    {
        return $this->hasMany(Potrero::class, 'finca_id', 'id');
    }



    /**
     * Opciones para Select Finca
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function optionsFinca(?int $distritoId = null, ?int $fincaId = null)
    {
        $query = static::select(['id', 'finca', 'distrito_id', 'slug']);

        if ($distritoId) {
            $query->where('distrito_id', $distritoId);
        }

        if ($fincaId) {
            $query->where('id', $fincaId);
            $finca = $query->first();
            if ($fincaId && $finca) {
                return [
                    'id' => $finca->id,
                    'label' => $finca->finca,
                ];
            }
        }

        return $query->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'label' => $data->finca,
                ];
            });
    }
}
