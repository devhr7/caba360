<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class cumplido_laborcampo extends Model
{
    //
    protected $table = 'cump_laborcampos';

    protected $fillable = [
        'id',
        'codigo',
        'fecha',
        'finca_id',
        'labor_id',

        'unidadmedida_id',
        'cantidadtotal',
        'fecha_cierre',
        'verificado',
    ];

    // Lote
    public function Finca(): BelongsTo
    {
        return $this->belongsTo(Finca::class, 'finca_id', 'id');
    }

    public function Labor(): BelongsTo
    {
        return $this->belongsTo(Labor::class, 'labor_id', 'id');
    }

    public function Unidadmedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'unidadmedida_id', 'id');
    }

    public function cumplido_laborcampodetallecuadrilla(): HasMany
    {
        return $this->hasMany(cumplido_laborcampodetallecuadrilla::class, 'cump_laborcampo_id', 'id');
    }

    public function cumplido_laborcampodetallelote(): HasMany
    {
        return $this->hasMany(cumplido_laborcampodetallelote::class, 'cump_laborcampo_id', 'id');
    }
}
