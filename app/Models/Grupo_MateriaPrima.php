<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Grupo_MateriaPrima extends Model
{
    use HasFactory;

    // Definir el nombre de la Tabla en la Base de Datos
    protected $table = 'grupo_materia_primas';

    // Campos de La Tabla
    protected $fillable = [
        'id',
        'slug',
        'GrupoMateriaPrima',
    ];

    /**
     * Slug
     */
     public static function boot()
     {
         parent::boot();
         // Cuando se Crea un Registro el slug se Crea Automaticamente
         static::creating(function ($reg) {
             $reg->slug = Str::slug($reg->GrupoMateriaPrima);
         });
         // Cuando se Edita un Registro el slug se Crea Automaticamente
         static::updating(function ($reg) {
             $reg->slug = Str::slug($reg->GrupoMateriaPrima);
         });
     }

     // Funcion Para Reconozca el Slug dentro de la ruta
     public function getRouteKey(): mixed
     {
         return $this->slug;
     }

     /**
     * Get the materias primas associated with the grupo materias primas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
     public function materiaPrima():HasMany
     {
         return $this->hasMany(MateriaPrima::class, 'GrupoMPrima_id', 'id');
     }


     /**
      * Devuelve las opciones de Grupo Materia Prima en formato json
      *
      * @param int|null $grupoMPrimaId
      * @return \Illuminate\Http\JsonResponse
      */
     public static function optionsGrupoMateriaPrima(?int $grupoMPrimaId = null,?array $selectgrupoMPrima = null)
     {
         $query = static::select(['id', 'slug','GrupoMateriaPrima']);

        if ($grupoMPrimaId) {
             $query->where('id', $grupoMPrimaId);
             $grupoMPrima = $query->first();
             if ($grupoMPrimaId && $grupoMPrima) {
                return [
                    'id' => $grupoMPrima->id,
                    'label' => $grupoMPrima->GrupoMateriaPrima,
                ];
            }
         }elseif($selectgrupoMPrima){
            $query->whereIn('id',$selectgrupoMPrima);
         }

         return $query->get()
             ->map(function ($data) {
                 return [
                     'id' => $data->id,
                     'label' => $data->GrupoMateriaPrima,
                 ];
             });
     }

}
