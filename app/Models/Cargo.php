<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Cargo extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'cargos';

    // Campos
    protected $fillable = [
        'slug',
        'cargo'
    ];

    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($reg) {
            $reg->slug = Str::slug($reg->cargo);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($reg) {
            $reg->slug = Str::slug($reg->cargo);
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
    public function Empleados(): HasMany
    {
        return $this->hasMany(Empleados::class, 'cargo_id', 'id');
    }

        public static function optionsCargo(?int $cargoId = null, ?array $cargosId = null)
    {
        $query = static::select(['id', 'cargo']);

        if ($cargoId) {
            $query->where('id', $cargoId);

            if ($datos = $query->first()) {
                return [
                    'id' => $datos->id,
                    'label' => $datos->cargo,
                ];
            }
        } else {
            if ($cargosId) {
                $query->whereIn('id', $cargosId);
            }
        }

        return $query->get()->map(fn($datos) => [
            'id' => $datos->id,
            'label' => $datos->cargo,
        ]);
    }
}
