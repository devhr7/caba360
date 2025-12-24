<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class RecordVisita extends Model
{

    use HasFactory;

    // Definir el nombre de la Tabla en la Base de Datos
    protected $table = 'record_visitas';

    // Campos de La Tabla
    protected $fillable = [
        'id',
        'slug',
        'Codigo',
        'fecha',
        'distrito_id',
        'finca_id',
        'lote_id',
        'RegLote_id',
        'AgricultorEncargado_id',
        'IngenieroAgronomo_id',
        'dias_emergencia',
        'hect_aplicacion',
        'diagnostico',
        'observaciones',
    ];


    /**
     * Slug
     */
     public static function boot()
     {
         parent::boot();
         // Cuando se Crea un Registro el slug se Crea Automaticamente
         static::creating(function ($reg) {
             $reg->slug = Str::slug($reg->Codigo," ",$reg->fecha);
         });
         // Cuando se Edita un Registro el slug se Crea Automaticamente
         static::updating(function ($reg) {
             $reg->slug = Str::slug($reg->Codigo," ",$reg->fecha);
         });
     }

     // Funcion Para Reconozca el Slug dentro de la ruta
     public function getRouteKey(): mixed
     {
         return $this->slug;
     }


public static function fechaPorCodigo($codigo, $formato = 'Y-m-d')
{
    if (empty($codigo)) {
        return "Mal"; // si viene null o vacío, no hace consulta
    }

    $record = self::where('Codigo', $codigo)->first();

    return $record?->fecha
        ? \Carbon\Carbon::parse($record->fecha)->format($formato)
        : "Mal2";
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

    // Agricultor Encargado
    public function AgricultorEncargado(): BelongsTo
    {
        return $this->belongsTo(Empleados::class, 'AgricultorEncargado_id', 'id');
    }
}
