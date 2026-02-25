<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MateriaPrima extends Model
{
    use HasFactory;

    // Definir el nombre de la Tabla en la Base de Datos
    protected $table = 'materia_primas';

    // Campos de La Tabla
    protected $fillable = [
        'id',
        'slug',
        'GrupoMPrima_id',
        'MateriaPrima',
        'PrecioUnit',
    ];

    /**
     * Slug
     */
     public static function boot()
     {
         parent::boot();
         // Cuando se Crea un Registro el slug se Crea Automaticamente
         static::creating(function ($reg) {
             $reg->slug = Str::slug($reg->MateriaPrima);
         });
         // Cuando se Edita un Registro el slug se Crea Automaticamente
         static::updating(function ($reg) {
             $reg->slug = Str::slug($reg->MateriaPrima);
         });
     }

     // Funcion Para Reconozca el Slug dentro de la ruta
     public function getRouteKey(): mixed
     {
         return $this->slug;
     }


     /**
      * Get the grupo materia prima associated with the materia prima.
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function grupoMPrima():BelongsTo
     {
         return $this->belongsTo(Grupo_MateriaPrima::class, 'GrupoMPrima_id', 'id');
     }


     /**
      * Options
      */

     /**
      * Devuelve las opciones de Materia Prima en formato json
      *
      * @param int|null $grupoMPrimaId
      * @return \Illuminate\Http\JsonResponse
      */
     public static function optionsMateriaPrima(?array $grupoMPrimaId = null, ?int $MateriaPrimaId = null)
     {
         $query = static::select(['id', 'slug','GrupoMPrima_id', 'MateriaPrima','PrecioUnit']);

         if ($grupoMPrimaId) {
             $query->whereIn('GrupoMPrima_id', $grupoMPrimaId);
         }

         if ($MateriaPrimaId) {
            $query->where('id', $MateriaPrimaId);
            $MateriaPrima = $query->first();
            if ($MateriaPrimaId && $MateriaPrima) {
                return [
                    'id' => $MateriaPrima->id,
                    'label' => $MateriaPrima->finca,
                    'PrecioUnit'=>$MateriaPrima->precioUnit,
                    'GrupoMPrima'=>[
                        'id'=> $MateriaPrima->GrupoMPrima_id,
                        'label' => Grupo_MateriaPrima::find($MateriaPrima->GrupoMPrima_id)->GrupoMateriaPrima,
                    ],

                ];
            }
        }

         return $query->get()
             ->map(function ($data) {
                 return [
                     'id' => $data->id,
                     'label' => $data->MateriaPrima,
                     'PrecioUnit'=>$data->PrecioUnit,
                    'GrupoMPrima'=>[
                        'id'=> $data->GrupoMPrima_id,
                        'label' => Grupo_MateriaPrima::find($data->GrupoMPrima_id)->GrupoMateriaPrima,
                    ],

                 ];
             });
     }

     public static function actualizarPreciosDesdeExcel($filePath)
     {
         $spreadsheet = IOFactory::load($filePath);
         $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

         foreach ($data as $row) {
             $materiaPrima = static::where('slug', $row['A'])->first();
             if ($materiaPrima && is_numeric($row['C'])) {
                 $materiaPrima->PrecioUnit = $row['C'];
                 $materiaPrima->save();
             }
         }
     }

}
