<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Maquina extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'maquinas';

    // Campos
    protected $fillable = [
        'slug',
        'CodMaq',
        'placa',
        'GrupoMaq_id',
        'modelo',
        'marca',
    ];

    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($reg) {
            $reg->slug = Str::slug($reg->CodMaq);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($reg) {
            $reg->slug = Str::slug($reg->CodMaq);
        });
    }

    // Funcion Para Reconozca el Slug dentro de la ruta
    public function getRouteKey(): mixed
    {
        return $this->slug;
    }

    public function CumplidoMaquinaria(): HasMany
    {
        return $this->hasMany(CumplidoMaquinaria::class, 'maquina_id', 'id');
    }


    /**
     * Relaciones Uno a muchos Invertido
     */
    // Distrito
    public function GrupoMaquina(): BelongsTo
    {
        return $this->belongsTo(GrupoMaquina::class, 'GrupoMaq_id', 'id');
    }


}
