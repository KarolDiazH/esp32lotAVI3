<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoteAve extends Model
{
    use HasFactory;

    protected $table = 'lote_aves';

    protected $fillable = [
        'codigo_lote',
        'raza',
        'cantidad_inicial',
        'cantidad_actual',
        'fecha_ingreso',
        'edad_semanas',
        'estado',
        'observaciones',
    ];
}
