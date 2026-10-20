<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alimentacion extends Model
{
    protected $fillable = ['device_id', 'dispositivo_id', 'peso_comida', 'esta_dispensando', 'contador_diario'];

    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class);
    }
}