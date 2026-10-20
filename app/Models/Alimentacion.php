<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alimentacion extends Model
{
    protected $fillable = ['device_id', 'dispositivo_id', 'peso_comida', 'esta_dispensando', 'contador_diario'];

    protected $casts = [
        'esta_dispensando' => 'boolean',
        'contador_diario'  => 'integer',
    ];

    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class);
    }
}