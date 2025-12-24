<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class CumplidoAplicacion extends Model
{
    use HasFactory;

    protected $table = 'cumplido_aplicacions';

    protected $fillable = [
        'slug',
        'codigo',
        'fecha',
        'HoraInicio',
        'HoraFinal',
        'distrito_id',
        'finca_id',
        'lote_id',
        'reg_lote_id',
        'HectLote',
        'RecordVisita_id',
        'CodRecordVisita',
        'CodSalidaAlmacen',
        'ResponsableAplicacion_id',
        'RiesgoLluvia',
        'Brisa',
        'HumedadLote',
        'Velocidad',
        'Seguridad',
        'EnpaquesEntregados',
        'Observaciones',
        'Coordinador_id',
        'JefeCampo_id',
        'verificado',
        'factura',
        'fecha_cierre',
    ];


    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($reg) {
            $reg->slug = Str::slug($reg->codigo . " " . $reg->RegLote_id);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($reg) {
            $reg->slug = Str::slug($reg->codigo . " " . $reg->RegLote_id);
        });
    }

    public function RegLote()
    {
        return $this->belongsTo(RegistroLotes::class, 'reg_lote_id');
    }

    // Funcion Para Reconozca el Slug dentro de la ruta
    public function getRouteKey(): mixed
    {
        return $this->slug;
    }

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'distrito_id');
    }

    public function finca()
    {
        return $this->belongsTo(Finca::class, 'finca_id');
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'lote_id');
    }

    public function reg_lote(): BelongsTo
    {
        return $this->belongsTo(RegistroLotes::class, 'reg_lote_id');
    }

    public function RecordVisita(): BelongsTo
    {
        return $this->belongsTo(RecordVisita::class, 'RecordVisita_id');
    }

    public function ResponsableAplicacion(): BelongsTo
    {
        return $this->belongsTo(contratista::class, 'ResponsableAplicacion_id', 'id');
    }

    public function Coordinador(): BelongsTo
    {
        return $this->belongsTo(Empleados::class, 'Coordinador_id', 'id');
    }

    public function JefeCampo(): BelongsTo
    {
        return $this->belongsTo(Empleados::class, 'JefeCampo_id', 'id');
    }

    /**
     * Get the cumplido apicacion that owns the CumplidoAplicacionProducto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function CumplidoAplicacionProducto(): HasMany
    {
        return $this->HasMany(CumplidoAplicacionProducto::class, 'CumplidoAplicacion_id', 'id');
    }

}
