<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class subtipogasto extends Model
{
    //

    protected $table = 'subtipogastos';

    protected $fillable = [
        'id',
        'slug',
        'codigo1',
        'codigo',
        'gasto_id',
        'tipogasto_id',
        'nombre',
        'valorunitario',
        'status_id',
    ];

    // Slug
    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($data) {
            $data->slug = Str::slug($data->codigo);
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($data) {
            $data->slug = Str::slug($data->codigo);
        });
    }



    public function tipogasto()
    {
        return $this->belongsTo(TipoGasto::class);
    }

    public function consolidadogastos()
    {
        return $this->hasMany(Consolidadogasto::class);
    }
}
