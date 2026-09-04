<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alimentacion;
use App\Models\Hidratacion;
use App\Models\TemperaturaHumedad;
use App\Models\ConteoHuevo;
use App\Models\LoteAve;
use Carbon\Carbon;

class SectorController extends Controller
{
    // 1. SECTOR ALIMENTACIÓN
    public function alimentacion()
    {
        $ultimo = Alimentacion::latest()->first() ?? (object)['peso_comida' => 2000, 'created_at' => now()];
        $peso = $ultimo->peso_comida;
        $capacidadMax = 2500;
        $porcentaje = min(100, max(0, round(($peso / $capacidadMax) * 100)));
        
        $poblacionAves = LoteAve::where('estado', '!=', 'Finalizado')->sum('cantidad_actual') ?: 100;
        
        $hoy = Carbon::today();
        $despachosHoy = Alimentacion::whereDate('created_at', $hoy)->where('device_id', 'WEB_ACTION')->count();
        $consumoHoyGramos = $despachosHoy * 100;
        $gramosPorAve = $poblacionAves > 0 ? round($consumoHoyGramos / $poblacionAves, 1) : 0;

        $historial = Alimentacion::orderBy('id', 'desc')->take(15)->get();

        return view('monitoreo.alimentacion', compact(
            'peso',
            'porcentaje',
            'capacidadMax',
            'poblacionAves',
            'consumoHoyGramos',
            'gramosPorAve',
            'despachosHoy',
            'historial',
            'ultimo'
        ));
    }

    // 2. SECTOR HIDRATACIÓN
    public function hidratacion()
    {
        $ultimo = Hidratacion::latest()->first() ?? (object)['nivel_agua' => 3.00, 'created_at' => now()];
        $nivel = $ultimo->nivel_agua;
        $capacidadMax = 5.0; // 5 Litros
        $porcentaje = min(100, max(0, round(($nivel / $capacidadMax) * 100)));

        $poblacionAves = LoteAve::where('estado', '!=', 'Finalizado')->sum('cantidad_actual') ?: 100;

        $hoy = Carbon::today();
        $despachosHoy = Hidratacion::whereDate('created_at', $hoy)->where('device_id', 'WEB_ACTION')->count();
        $consumoHoyLitros = $despachosHoy * 0.25;

        $historial = Hidratacion::orderBy('id', 'desc')->take(15)->get();

        return view('monitoreo.hidratacion', compact(
            'nivel',
            'porcentaje',
            'capacidadMax',
            'poblacionAves',
            'consumoHoyLitros',
            'despachosHoy',
            'historial',
            'ultimo'
        ));
    }

    // 3. SECTOR TEMPERATURA Y HUMEDAD
    public function temperatura()
    {
        $ultimo = TemperaturaHumedad::latest()->first() ?? (object)['temperature' => 24.5, 'humidity' => 60, 'created_at' => now()];
        $clima = TemperaturaHumedad::where('temperature', '>', 0);

        $historial = (clone $clima)->orderBy('id', 'desc')->take(15)->get();
        $promedioTemp = round((clone $clima)->avg('temperature') ?? 24, 1);
        $tempMax = (clone $clima)->max('temperature') ?? 28;
        $tempMin = (clone $clima)->min('temperature') ?? 18;
        $humPromedio = round((clone $clima)->avg('humidity') ?? 60, 1);

        return view('monitoreo.temperatura', compact(
            'ultimo',
            'historial',
            'promedioTemp',
            'tempMax',
            'tempMin',
            'humPromedio'
        ));
    }

    // 4. SECTOR CONTEO DE HUEVOS (BANDA TRANSPORTADORA)
    public function huevos()
    {
        $totalHoy = ConteoHuevo::whereDate('created_at', Carbon::today())->sum('cantidad');
        $maples = floor($totalHoy / 30);
        $sueltos = $totalHoy % 30;

        $historial = ConteoHuevo::orderBy('id', 'desc')->take(15)->get();
        $poblacionAves = LoteAve::where('estado', '!=', 'Finalizado')->sum('cantidad_actual') ?: 100;
        $eficiencia = min(100, round(($totalHoy / $poblacionAves) * 100, 1));
        $ultimo = ConteoHuevo::latest()->first();

        return view('monitoreo.huevos', compact(
            'totalHoy',
            'maples',
            'sueltos',
            'historial',
            'eficiencia',
            'poblacionAves',
            'ultimo'
        ));
    }
}