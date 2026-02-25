<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ppt_detalle_costo extends Model
{
    //

    protected $table = 'ppt_detalle_costos';

    protected $fillable = ['ppt_id', 'finca_id', 'gasto_id', 'costoxhect'];

    public function ppt_general()
    {
        return $this->belongsTo(ppt_general::class, 'ppt_id', 'id');
    }

    public function finca()
    {
        return $this->belongsTo(Finca::class, 'finca_id', 'id');
    }

    public function gasto()
    {
        return $this->belongsTo(gasto::class, 'gasto_id', 'id');
    } 

    
}
