<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConteoHuevo extends Model
{
    // SIN ESTO, DA ERROR AL GUARDAR
    protected $fillable = ['device_id', 'weight_raw', 'cantidad'];
}