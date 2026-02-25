<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class consolidadogasto extends Model
{
    //

    protected $table = 'consolidadogastos';


    protected $fillable = [
    'fecha',
    'reglote_id',
    'comprobante',
    'cantidad',
    'preciounitario',
    'total',
    'gasto_id',
    'tipogasto_id',
    'subtipogasto_id',
    'observaciones'
    ];

    public static function getByRegLoteId($id_regLote)
    {
        return self::where('reglote_id', $id_regLote)->get();
    }

    public function gasto()
    {
        return $this->belongsTo(gasto::class);
    }

    public function tipogasto()
    {
        return $this->belongsTo(TipoGasto::class);
    }

    public function subtipogasto()
    {
        return $this->belongsTo(subtipogasto::class);
    }

    public function reglote()
    {
        return $this->belongsTo(RegistroLotes::class);
    }

}
