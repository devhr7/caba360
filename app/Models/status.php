<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class status extends Model
{
    use HasFactory;

    protected $table = 'statuses';
    protected $fillable = ['id',  'status'];


    public function RegistroLotes(): HasMany
    {
        return $this->hasMany(RegistroLotes::class, 'status_id', 'id');
    }

    public function ppt_generals(): HasMany
    {
        return $this->hasMany(ppt_general::class, 'status_id', 'id');
    }

    public static function optionsStatus(?int $statusId = null)
    {
        $query = static::select(['id', 'status']);


        if ($statusId) {
            $query->where('id', $statusId);
            $status = $query->first();
            if ($statusId && $status) {
                return [
                    'id' => $status->id,
                    'label' => $status->status,
                ];
            }
        }

        return $query->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'label' => $data->status,
                ];
            });
    }
}
