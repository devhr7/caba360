<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class cumplido_laborcampodetallelote extends Model
{
    //
    protected $table = 'cump_laborcampodetlotes';

    protected $fillable = [
        'id',
        'cump_laborcampo_id',
        'lote_id',
        'reg_lote_id',
        'cantidad',
        'observaciones',
    ];

    public function CumpLaborcampo(): BelongsTo
    {
        return $this->belongsTo(cumplido_laborcampo::class, 'cump_laborcampo_id', 'id');
    }

    public function Lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'lote_id', 'id');
    }

    public function RegLote(): BelongsTo
    {
        return $this->belongsTo(RegistroLotes::class, 'reg_lote_id', 'id');
    }
}
