<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ProductoRecordVisitas extends Model
{
    use HasFactory;

    // Definir el nombre de la Tabla en la Base de Datos
    protected $table = 'producto_record_visitas';

    // Campos de La Tabla
    protected $fillable = [
        'slug',
        'RecordVisita_id',
        'GrupoMateriaPrima_id',
        'producto_id',
        'Dosis_por_Ha',
        'cantidad_Total',
        'fecha_estimada_aplicacion',
    ];

    /**
     * Slug
     */
     public static function boot()
     {
         parent::boot();
         // Cuando se Crea un Registro el slug se Crea Automaticamente
         static::creating(function ($reg) {
             $reg->slug = Str::slug($reg->RecordVisita_id . " ". $reg->producto_id);
         });
         // Cuando se Edita un Registro el slug se Crea Automaticamente
         static::updating(function ($reg) {
            $reg->slug = Str::slug($reg->RecordVisita_id ." ". $reg->producto_id);
         });
     }

     // Funcion Para Reconozca el Slug dentro de la ruta
     public function getRouteKey(): mixed
     {
         return $this->slug;
     }


     /**
      * Get the grupo materia prima associated with the producto record visitas.
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function grupoMPrima():BelongsTo
     {
         return $this->belongsTo(Grupo_MateriaPrima::class, 'GrupoMateriaPrima_id', 'id');
     }


     public function Producto():BelongsTo
     {
         return $this->belongsTo(MateriaPrima::class, 'producto_id', 'id');
     }


     /**
      * Get the producto record visitas associated with the grupo materia prima.
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function recordVisita():BelongsTo
     {
         return $this->belongsTo(RecordVisita::class, 'RecordVisita_id', 'id');
     }

}
