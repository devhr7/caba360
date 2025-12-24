<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CumplidoMaquinaria extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'cumplido_maquinarias';

    // Campos
    protected $fillable = [
        'id',
        'slug',
        'Codigo',
        'distrito_id',
        'finca_id',
        'lote_id',
        'RegLote_id',
        'Externo',
        'Finca',
        'Lote',
        'maquina_id',
        'tipolabor_id',
        'labor_id',
        'fecha',
        'operario_id',
        'HorometroInicial',
        'HorometroFinal',
        'HorometroTotal',
        'GalCombustible',
        'cant',
        'valor_unit',
        'valor_total',
        'garantia',
        'Observaciones',
        'verificado',
        'fecha_cierre',
    ];

    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($reg) {
            $reg->slug = Str::slug($reg->codigo, " ", $reg->RegLote_id);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($reg) {
            $reg->slug = Str::slug($reg->codigo, " ", $reg->RegLote_id);
        });
    }

    // Funcion Para Reconozca el Slug dentro de la ruta
    public function getRouteKey(): mixed
    {
        return $this->slug;
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

    // Lote
    public function Lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'lote_id', 'id');
    }

    // RegistroLotes
    public function RegistroLotes(): BelongsTo
    {
        return $this->belongsTo(RegistroLotes::class, 'RegLote_id', 'id');
    }

    // RegistroLotes
    public function Labor(): BelongsTo
    {
        return $this->belongsTo(Labor::class, 'labor_id', 'id');
    }

    // Tipo Labor
    public function TipoLabor(): BelongsTo
    {
        return $this->belongsTo(TipoLabor::class, 'tipolabor_id', 'id');
    }

    // RegistroLotes
    public function Empleados(): BelongsTo
    {
        return $this->belongsTo(Empleados::class, 'operario_id', 'id');
    }

    // RegistroLotes
    public function Maquina(): BelongsTo
    {
        return $this->belongsTo(Maquina::class, 'maquina_id', 'id');
    }
}
