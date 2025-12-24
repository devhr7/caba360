<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Empleados extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'empleados';

    // Campos
    protected $fillable = [
        'slug',
        'identificacion',
        'nombre1',
        'nombre2',
        'apellido1',
        'apellido2',
        'cargo_id',
        'FechaInicio',
        'FechaRetiro',
        'salario',
    ];

    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($reg) {
            $reg->slug = Str::slug($reg->nombre1 . " " . $reg->nombre2 . " " . $reg->apellido1 . " " . $reg->apellido2);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($reg) {
            $reg->slug = Str::slug($reg->nombre1 . " " . $reg->nombre2 . " " . $reg->apellido1 . " " . $reg->apellido2);
        });
    }

    // Funcion Para Reconozca el Slug dentro de la ruta
    public function getRouteKey(): mixed
    {
        return $this->slug;
    }



    public function CumplidoMaquinaria(): HasMany
    {
        return $this->hasMany(CumplidoMaquinaria::class, 'operario_id', 'id');
    }

    public function CumplidoOrdenServicio(): HasMany
    {
        return $this->hasMany(CumplidoOrdenServicio::class, 'Autorizacion_id', 'id');
    }

    /**
     * Relaciones Uno a muchos Invertido
     */
    // Distrito
    public function Cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'cargo_id', 'id');
    }



    /**
     * Relaciones Uno a Muchos Invertido
     * HasMany CumplidoAplicacion con el campo ResponsableAplicacion_id
     * @return HasMany
     */
    public function Coordinador(): HasMany
    {
        return $this->hasMany(CumplidoAplicacion::class, 'Coordinador_id', 'id');
    }

    public function JefeCampo(): HasMany
    {
        return $this->hasMany(CumplidoAplicacion::class, 'JefeCampo_id', 'id');
    }

    /**
     * Devuelve una lista de empleados con su nombre y apellido completo, filtrando por el cargo_id pasado por parametro.
     * Si no se pasa el cargo_id, devuelve la lista de todos los empleados.
     * @param int|null $cargoId
     * @return \Illuminate\Support\Collection
     */


    public static function optionsEmpleadoPorCargo(?int $cargoId = null, ?int $empleadoId = null)
    {
        $query = static::select(['id', 'nombre1', 'nombre2', 'apellido1', 'apellido2', 'cargo_id', 'slug']);
        if ($cargoId) {
            $query->where('cargo_id', $cargoId);
        }

        if ($empleadoId) {
            $query->where('id', $empleadoId);
            $empleado = $query->first();
            if ($empleadoId && $empleado) {
                return [
                    'id' => $empleado->id,
                    'label' => $empleado->nombre1 . ' ' . $empleado->nombre2 . ' ' . $empleado->apellido1 . ' ' . $empleado->apellido2,
                ];
            }
        }

        return $query->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'label' => $data->nombre1 . ' ' . $data->nombre2 . ' ' . $data->apellido1 . ' ' . $data->apellido2,
                ];
            });
    }



    public static function optionsEmpleado(?int $empleadoId = null ,?array $cargoId = null )
    {
        $query = static::select(['id', 'nombre1', 'nombre2', 'apellido1', 'apellido2', 'cargo_id', 'slug']);
        if (!is_null($cargoId)) {
            $query->wherein('cargo_id', $cargoId);
        }

        if ($empleadoId) {
            $query->where('id', $empleadoId);
            $empleado = $query->first();
            if ($empleadoId && $empleado) {
                return [
                    'id' => $empleado->id,
                    'label' => $empleado->nombre1 . ' ' . $empleado->nombre2 . ' ' . $empleado->apellido1 . ' ' . $empleado->apellido2,
                ];
            }
        }

        return $query->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'label' => $data->nombre1 . ' ' . $data->nombre2 . ' ' . $data->apellido1 . ' ' . $data->apellido2,
                ];
            });
    }
}
