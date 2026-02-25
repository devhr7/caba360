<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class GrupoMaquina extends Model
{
    use HasFactory;

     // Tabla
     protected $table = 'grupo_maquinas';

     // Campos
     protected $fillable = [
         'slug',
         'GrupoMaquina'
     ];

     // Slug
     public static function boot()
     {
         parent::boot();
         // Cuando se Crea un Registro el slug se Crea Automaticamente
         static::creating(function ($reg) {
             $reg->slug = Str::slug($reg->GrupoMaquina);
         });
         // Cuando se Edita un Registro el slug se Crea Automaticamente
         static::updating(function ($reg) {
             $reg->slug = Str::slug($reg->GrupoMaquina);
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
    public function Maquina(): HasMany
    {
        return $this->hasMany(Maquina::class, 'GrupoMaq_id', 'id');
    }
}
