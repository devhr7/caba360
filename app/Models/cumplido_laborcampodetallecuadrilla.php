<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class cumplido_laborcampodetallecuadrilla extends Model
{
    //
    protected $table = 'cump_laborcampodetcuadrillas';

    protected $fillable = [
        'id',
        'cump_laborcampo_id',
        'personal_id',
        'cantidad',
        'costo_unit',
        'total_bonificacion',
        'observaciones',
    ];


    public function CumpLaborcampo(): BelongsTo
    {
        return $this->belongsTo(cumplido_laborcampo::class, 'cump_laborcampo_id', 'id');
    }

    public function Personal(): BelongsTo
    {
        return $this->belongsTo(Empleados::class, 'personal_id', 'id');
    }
}
