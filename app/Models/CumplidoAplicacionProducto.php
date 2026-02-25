<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CumplidoAplicacionProducto extends Model
{
    use HasFactory;
    protected $table = 'cumplido_aplicacion_productos';
    protected $fillable = [
        'slug',
        'CumplidoAplicacion_id',
        'GrupoMateriaPrima_id',
        'producto_id',
        'labor_id',
        'Dosis_por_Ha',
        'cantidad_Total',
        'PrecioUnit',
        'PrecioTotal',
    ];

    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($reg) {

            if (is_null($reg->labor_id)) {
                $reg->slug = Str::slug($reg->CumplidoAplicacion_id . "-P-" . $reg->producto_id);
            } else {
                $reg->slug = Str::slug($reg->CumplidoAplicacion_id . "-L-" . $reg->labor_id);
            }
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($reg) {
            if (is_null($reg->labor_id)) {
                $reg->slug = Str::slug($reg->CumplidoAplicacion_id . "-P-" . $reg->producto_id);
            } else {
                $reg->slug = Str::slug($reg->CumplidoAplicacion_id . "-L-" . $reg->labor_id);
            }
        });
    }

    // Funcion Para Reconozca el Slug dentro de la ruta
    public function getRouteKey(): mixed
    {
        return $this->slug;
    }

    /**
     * Get the grupo materia prima associated with the cumplido apicacion producto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grupoMPrima(): BelongsTo
    {
        return $this->belongsTo(Grupo_MateriaPrima::class, 'GrupoMateriaPrima_id', 'id');
    }


    /**
     * Get the producto associated with the cumplido apicacion producto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Producto(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class, 'producto_id', 'id');
    }

    /**
     * Get the cumplido apicacion that owns the CumplidoApicacionProducto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function CumlidoAplicacion(): BelongsTo
    {
        return $this->Belongsto(CumplidoAplicacion::class, 'CumplidoAplicacion_id', 'id');
    }


  
    public function Labor(): BelongsTo
    {
        return $this->BelongsTo(Labor::class, 'labor_id', 'id');
    }
}
