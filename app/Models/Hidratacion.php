<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hidratacion extends Model
{
    use HasFactory;

    protected $fillable = ['device_id', 'dispositivo_id', 'nivel_agua', 'flotador_bajo', 'flotador_alto', 'bomba_encendida'];

    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class);
    }
}