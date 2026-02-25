<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClasificacionCC_Has_Finca extends Model
{
    //
    protected $table = 'clasif_cc_has_fincas';

    protected $fillable = [
        'ClasificacionCentroCosto_id',
        'finca_id',
    ];

    public function clasificacionCentroCosto():BelongsTo
    {
        return $this->belongsTo(ClasificacionCentroCosto::class, 'ClasificacionCentroCosto_id');
    }

    public function finca():BelongsTo
    {
        return $this->belongsTo(Finca::class, 'finca_id');
    }
}
