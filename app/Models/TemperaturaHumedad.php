<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemperaturaHumedad extends Model
{
    // SIN ESTO, DA ERROR AL GUARDAR
    protected $fillable = ['device_id', 'temperature', 'humidity'];
}