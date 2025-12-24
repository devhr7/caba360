<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ppt_detalle_meta extends Model
{
    //

    protected $table = 'ppt_detalle_metas';

    protected $fillable = ['ppt_id', 'finca_id', 'hect_mensual', 'rend_bultoxhect'];

    public function ppt_general()
    {
        return $this->belongsTo(ppt_general::class, 'ppt_id', 'id');
    }

    public function finca()
    {
        return $this->belongsTo(finca::class, 'finca_id', 'id');
    }
    
}
