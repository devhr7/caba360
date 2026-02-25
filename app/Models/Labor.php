<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Labor extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'labors';

    // Campos
    protected $fillable = [
        'slug',
        'status',
        'labor',
        'TipoCumplido_id',
        'TipoLabor_id',
        'CostoHect',
        'CumpMaqV',
        'CumpApliV',
        'CumpOrdV',
        'Hect'
    ];

    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se crea un registro, el slug se crea automáticamente
        static::creating(function ($reg) {
            // Si no se asignó un slug manualmente, se genera uno automáticamente
            if (empty($reg->slug)) {
                $reg->slug = Str::slug($reg->labor . $reg->TipoCumplido_id);
            }

            if (is_null($reg->status)) {
                $reg->status = 1;
            }
        });
        // Cuando se actualiza un registro, el slug se crea automáticamente
        static::updating(function ($reg) {
            // Si no se asignó un slug manualmente, se genera uno automáticamente
            if (empty($reg->slug)) {
                $reg->slug = Str::slug($reg->labor . $reg->TipoCumplido_id);
            }
        });
    }

    // Funcion Para Reconozca el Slug dentro de la ruta
    public function getRouteKey(): mixed
    {
        return $this->slug;
    }

    public function CumplidoAplicacionProducto(): HasMany
    {
        return $this->HasMany(CumplidoAplicacionProducto::class, 'labor_id', 'id');
    }

    public function CumplidoOrdenServicioDetalle(): HasMany
    {
        return $this->HasMany(CumplidoOrdenServicioDetalle::class, 'Labor_id', 'id');
    }


    public function CumpLaborcampo(): HasMany
    {
        return $this->hasMany(cumplido_laborcampo::class, 'labor_id', 'id');
    }
    /**
     * Relaciones Uno a muchos Invertido
     */
    // Tipo Labor
    public function TipoLabor(): BelongsTo
    {
        return $this->belongsTo(TipoLabor::class, 'TipoLabor_id', 'id');
    }

    // Tipo Cumplido
    public function TipoCumplido(): BelongsTo
    {
        return $this->belongsTo(TipoCumplido::class, 'TipoCumplido_id', 'id');
    }

    public static function optionsLabor(?array $laborsId = null, ?int $TipoCumplidoId = null, ?int $laborId = null)
    {
        $query = static::select(['id', 'status', 'labor', 'CostoHect', 'TipoCumplido_id', 'CumpMaqV', 'CumpApliV', 'CumpOrdV'])
            ->where('status', 1);

        if ($laborId) {
            $query->where('id', $laborId);

            if ($labor = $query->first()) {
                return [
                    'id' => $labor->id,
                    'label' => $labor->labor,
                    'CostoHect' => $labor->CostoHect,
                    'CumpMaqV' => $labor->CumpMaqV,
                    'CumpApliV' => $labor->CumpApliV,
                    'CumpOrdV' => $labor->CumpOrdV,
                    'status' => $labor->status,
                ];
            }
        } else {
            if ($TipoCumplidoId) {
                $query->where('TipoCumplido_id', $TipoCumplidoId);
            }

            if ($laborsId) {
                $query->whereIn('id', $laborsId);
            }
        }

        return $query->get()->map(fn($labor) => [
            'id' => $labor->id,
            'status' => $labor->status,
            'label' => $labor->labor,
            'CostoHect' => $labor->CostoHect,
            'CumpMaqV' => $labor->CumpMaqV,
            'CumpApliV' => $labor->CumpApliV,
            'CumpOrdV' => $labor->CumpOrdV,
        ]);
    }
}
