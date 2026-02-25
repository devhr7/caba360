<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class consolidadoingreso extends Model
{
    protected $fillable = [
        'fecha',
        'reglote_id',
        'documento',
        'kilogramos',
        'totalventa',
        'venta',
        'observaciones',
    ];
}
