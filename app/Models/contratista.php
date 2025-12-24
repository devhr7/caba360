<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class contratista extends Model
{
    //
    protected $table = 'contratistas';


    protected $fillable = [
        'slug',
        'identificacion',
        'nombre'
    ];

    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($reg) {
            $reg->slug = Str::slug($reg->nombre);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($reg) {
            $reg->slug = Str::slug($reg->nombre);
        });
    }

    // Funcion Para Reconozca el Slug dentro de la ruta
    public function getRouteKey(): mixed
    {
        return $this->slug;
    }

    public function ResponsableAplicacion(): HasMany
    {
        return $this->hasMany(CumplidoAplicacion::class, 'ResponsableAplicacion_id', 'id');
    }

    public function CumplidoOrdenServicio(): HasMany
    {
        return $this->hasMany(CumplidoOrdenServicio::class, 'Responsable_id', 'id');
    }


    public static function optionsContratista(?int $contratistaId = null)
    {
        $query = static::select(['id', 'identificacion', 'nombre', 'slug']);

        if ($contratistaId) {
            $query->where('id', $contratistaId);
            $contratista = $query->first();
            if ($contratistaId && $contratista) {
                return [
                    'id' => $contratista->id,
                    'identificacion' => $contratista->identificacion,
                    'label' => $contratista->nombre,
                ];
            }
        }

        return $query->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'identificacion' => $data->identificacion,
                    'label' => $data->nombre,
                ];
            });
    }
}
