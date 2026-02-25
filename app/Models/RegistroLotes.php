<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class RegistroLotes extends Model
{
    use HasFactory;

    // Tabla
    protected $table = 'registro_lotes';

    // Campos
    protected $fillable = [
        'id',
        'slug',
        'status_id',
        'Codigo',
        'NombreLote',
        'Hect',
        'FechaInicio',
        'FechaSiembra',
        'FechaGerminacion',
        'FechaRecoleccion',
        'FechaVenta',
        'TipoCultivo_id',
        'TipoVariedad_id',
        'lote_id',
        'finca_id',
        'distrito_id',
    ];




    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($reglote) {
            $reglote->slug = Str::slug($reglote->finca->finca . " " . $reglote->lote->lote . " " . $reglote->Codigo);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($reglote) {
            $reglote->slug = Str::slug($reglote->finca->finca . " " . $reglote->lote->lote . " " . $reglote->Codigo);
        });
    }

    // Funcion Para Reconozca el Slug dentro de la ruta
    public function getRouteKey(): mixed
    {
        return $this->slug;
    }

    /**
     * Relaciones Uno a muchos Invertido
     */
    // Estatus

    public function status(): BelongsTo
    {
        return $this->BelongsTo(status::class, 'status_id', 'id');
    }

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

    // Tipo Cultivo
    public function TipoCultivo(): BelongsTo
    {
        return $this->belongsTo(TipoCultivo::class, 'TipoCultivo_id', 'id');
    }
    // Tipo Variedad
    public function TipoVariedad(): BelongsTo
    {
        return $this->belongsTo(TipoVariedad::class, 'TipoVariedad_id', 'id');
    }

    public function CumplidoMaquinaria(): HasMany
    {
        return $this->hasMany(CumplidoMaquinaria::class, 'RegLote_id', 'id');
    }

    public function CumplidoOrdenServicioDetalle(): BelongsTo
    {
        return $this->BelongsTo(CumplidoOrdenServicioDetalle::class, 'RegLote_id', 'id');
    }



    public static function optionsRegLote($status = null, ?int $LoteId = null, ?int $RegLoteId = null)
    {
        $query = static::select(['id', 'Codigo', 'NombreLote', 'Hect', 'FechaRecoleccion', 'lote_id', 'FechaInicio', 'status_id']);

        if ($status) {
            $query->whereIn('status_id', $status);
        }

        if ($LoteId) {
            $query->where('lote_id', $LoteId);
        }

        if ($RegLoteId) {
            $query->where('id', $RegLoteId);
            $RegLote = $query->first();
            if ($RegLoteId && $RegLote) {
                return [
                    'id' => $RegLote->id,
                    'label' => $RegLote->Codigo . ' | ' . $RegLote->NombreLote . ' | ' . $RegLote->Hect,
                    'Codigo' => $RegLote->Codigo, //
                    'FechaInicio' => $RegLote->FechaInicio, //
                    'Hect' => $RegLote->Hect, //
                ];
            }
        }

        return $query->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'label' => $data->Codigo . ' | ' . $data->NombreLote . ' | ' . $data->Hect,
                    'Codigo' => $data->Codigo, //
                    'FechaInicio' => $data->FechaInicio, //
                    'Hect' => $data->Hect, //
                ];
            });
    }
}
