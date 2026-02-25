<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class gasto extends Model
{
    //
    protected $table = 'gastos';

    protected $fillable = [
        'id',
        'codigo',
        'nombre',
        'status_id',
    ];


    public function consolidadogastos()
    {
        return $this->hasMany(Consolidadogasto::class);
    }

    public function tipogastos()
    {
        return $this->hasMany(Tipogasto::class);
    }

    public static function optionsGasto(?int $gasto_id = null)
    {
        $query = static::select(['id', 'codigo', 'nombre']);


        if ($gasto_id) {
            $query->where('id', $gasto_id);
            $data = $query->first();
            if ($gasto_id && $data) {
                return [
                    'id' => $data->id,
                    'label' => $data->nombre,
                ];
            }
        }
        return $query->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'label' => $data->nombre,
                ];
            });
    }
}
