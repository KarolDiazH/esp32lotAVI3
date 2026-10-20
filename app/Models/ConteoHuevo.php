<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConteoHuevo extends Model
{
    protected $fillable = ['device_id', 'dispositivo_id', 'weight_raw', 'cantidad'];

    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class);
    }
}