<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemperaturaHumedad extends Model
{
    protected $fillable = ['device_id', 'dispositivo_id', 'temperature', 'humidity'];

    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class);
    }
}