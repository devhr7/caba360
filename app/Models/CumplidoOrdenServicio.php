<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;


class CumplidoOrdenServicio extends Model
{
    use HasFactory;


    // Tabla
    protected $table = 'cump_orden_servicios';

    // Campos
    protected $fillable = [
        'id',
        'slug',
        'codigo',
        'fecha',
        'Responsable_id',
        'Autorizacion_id',
        'factura',
        'fecha_cierre',
    ];

    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($reg) {
            $reg->slug = Str::slug($reg->codigo);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($reg) {
            $reg->slug = Str::slug($reg->codigo);
        });
    }

    // Funcion Para Reconozca el Slug dentro de la ruta
    public function getRouteKey(): mixed
    {
        return $this->slug;
    }

    public function contratista(): BelongsTo
    {
        return $this->BelongsTo(contratista::class, 'Responsable_id', 'id');
    }

    public function Empleados(): BelongsTo
    {
        return $this->BelongsTo(Empleados::class, 'Autorizacion_id', 'id');
    }


    public function CumplidoOrdenServicioDetalle(): HasMany
    {
        return $this->hasMany(CumplidoOrdenServicioDetalle::class, 'CumplidoOrdenServicio_id', 'id');
    }
}
