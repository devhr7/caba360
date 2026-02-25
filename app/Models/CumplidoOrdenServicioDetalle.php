<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;


/**
 *
ALTER TABLE caba360.cump_orden_servicio_dets ADD RecordVisita varchar(100) NULL;
ALTER TABLE caba360.cump_orden_servicio_dets ADD RecordVisita_id BIGINT UNSIGNED NULL;
 */

class CumplidoOrdenServicioDetalle extends Model
{
    use HasFactory;

    protected $table = 'cump_orden_servicio_dets';

    // Campos
    protected $fillable = [
        'id',
        'CumplidoOrdenServicio_id',
        'TipoCentroCosto_id',
        'Interno',
        'finca_id',
        'DestinoServicio',
        'Lote_id',
        'potrero_id',
        'RegLote_id',
        'Labor_id',
        'DetalleLabor',
        'Cantidad',
        'UnidadMedida_id',
        'ValorUnit',
        'Total',
        'RecordVisita',
        'RecordVisita_id',
        'Observaciones',


    ];


    /**
     * Relaciones
     */

    public function potrero(): BelongsTo
    {
        return $this->BelongsTo(Potrero::class, 'potrero_id', 'id');
    }

    public function CumplidoOrdenServicio(): BelongsTo
    {
        return $this->BelongsTo(CumplidoOrdenServicio::class, 'CumplidoOrdenServicio_id', 'id');
    }


    public function ClasificacionCentroCosto(): BelongsTo
    {
        return $this->BelongsTo(ClasificacionCentroCosto::class, 'TipoCentroCosto_id', 'id');
    }

    public function Finca(): BelongsTo
    {
        return $this->BelongsTo(Finca::class, 'finca_id', 'id');
    }

    public function Lote(): BelongsTo
    {
        return $this->BelongsTo(Lote::class, 'Lote_id', 'id');
    }

    public function RegLote(): BelongsTo
    {
        return $this->BelongsTo(RegistroLotes::class, 'RegLote_id', 'id');
    }

    public function Labor(): BelongsTo
    {
        return $this->BelongsTo(Labor::class, 'Labor_id', 'id');
    }

    public function UnidadMedida(): BelongsTo
    {
        return $this->BelongsTo(UnidadMedida::class, 'UnidadMedida_id', 'id');
    }
}
