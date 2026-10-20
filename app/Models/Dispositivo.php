<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispositivo extends Model
{
    protected $fillable = ['device_id', 'nombre', 'estado', 'lote_aves_id', 'last_seen_at'];

    protected $casts = ['last_seen_at' => 'datetime'];

    public function loteAve()
    {
        return $this->belongsTo(LoteAve::class, 'lote_aves_id');
    }

    public function temperaturaHumedads()
    {
        return $this->hasMany(TemperaturaHumedad::class);
    }

    public function conteoHuevos()
    {
        return $this->hasMany(ConteoHuevo::class);
    }

    public function alimentacions()
    {
        return $this->hasMany(Alimentacion::class);
    }

    public function hidratacions()
    {
        return $this->hasMany(Hidratacion::class);
    }
}