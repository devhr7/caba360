<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Distrito extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'distritos';

    // Campos
    protected $fillable = [
        'distrito',
        'slug'
    ];

    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($distrito) {
            $distrito->slug = Str::slug($distrito->distrito);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($distrito) {
            $distrito->slug = Str::slug($distrito->distrito);
        });
    }

    // Funcion Para Reconozca el Slug dentro de la ruta
    public function getRouteKey(): mixed
    {
        return $this->slug;
    }

    /**
     * Relaciones
     */

    // Finca
    public function Finca(): HasMany
    {
        return $this->hasMany(Finca::class, 'distrito_id', 'id');
    }

    // Lote
    public function Lote(): HasMany
    {
        return $this->hasMany(Lote::class, 'distrito_id', 'id');
    }

    // Reg Lote
    public function RegLote(): HasMany
    {
        return $this->hasMany(RegistroLotes::class, 'distrito_id', 'id');
    }

    public function CumplidoMaquinaria(): HasMany
    {
        return $this->hasMany(CumplidoMaquinaria::class, 'distrito_id', 'id');
    }


    public static function optionsDistrito(?int $distritoId = null)
    {
        $query = static::select(['id', 'distrito', 'slug']);

        if ($distritoId) {
            $query->where('id', $distritoId);
            $distrito = $query->first();
            if ($distritoId) {
                return [
                    'id' => $distrito->id,
                    'label' => $distrito->distrito,
                ];
            }
        }

        return $query->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'label' => $data->distrito,
                ];
            });
    }
}
