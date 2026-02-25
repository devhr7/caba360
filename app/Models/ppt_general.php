<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;


class ppt_general extends Model
{
    //
    protected $table = 'ppt_generals';
    protected $fillable = ['slug', 'status_id', 'fecha_ini', 'fecha_fin'];


    public static function boot()
    {
        parent::boot();
        // Cuando se Crea un Registro el slug se Crea Automaticamente
        static::creating(function ($reg) {
            $reg->slug = Str::slug("PPT" . date('Ym', strtotime($reg->fecha_ini)) . date('Ym', strtotime($reg->fecha_fin)));
        });
        // Cuando se Edita un Registro el slug se Crea Automaticamente
        static::updating(function ($reg) {
            $reg->slug = Str::slug("PPT" . date('Ym', strtotime($reg->fecha_ini)) . date('Ym', strtotime($reg->fecha_fin)));
        });
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(status::class, 'status_id', 'id');
    }
    public function ppt_detalle_costos(): HasMany
    {
        return $this->hasMany(ppt_detalle_costo::class, 'ppt_id', 'id');
    }
    public function ppt_detalle_metas(): HasMany
    {
        return $this->hasMany(ppt_detalle_meta::class, 'ppt_id', 'id');
    }
}
