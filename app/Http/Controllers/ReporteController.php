<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemperaturaHumedad;
use App\Models\ConteoHuevo;
use App\Models\Alimentacion;
use App\Models\Hidratacion;
use App\Models\LoteAve;
use Carbon\Carbon;

class ReporteController extends Controller
{
    // 1. REPORTE DIARIO CON DATOS REALES DE TEMPERATURA
    public function diario(Request $request)
    {
        // Si no eligen fecha, toma la última fecha con datos del ESP32
        $ultimaFecha = TemperaturaHumedad::latest('created_at')->value('created_at');
        $fechaDefault = $ultimaFecha ? Carbon::parse($ultimaFecha)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        
        $fecha = $request->input('fecha', $fechaDefault);
        $inicioDia = Carbon::parse($fecha)->startOfDay();
        $finDia = Carbon::parse($fecha)->endOfDay();

        // 1. CONSULTA REAL DE TEMPERATURA Y HUMEDAD DE TU TABLA
        $climaDia = TemperaturaHumedad::whereBetween('created_at', [$inicioDia, $finDia])
            ->where('temperature', '>', 0); // Descarta lecturas en 0 de inicio

        $tempPromedio = round($climaDia->avg('temperature') ?? 0, 1);
        $tempMax = $climaDia->max('temperature') ?? 0;
        $tempMin = $climaDia->min('temperature') ?? 0;
        $humPromedio = round($climaDia->avg('humidity') ?? 0, 1);
        $totalLecturas = $climaDia->count();

        // 2. PRODUCCIÓN DE HUEVOS Y MAPLES
        $totalHuevos = ConteoHuevo::whereBetween('created_at', [$inicioDia, $finDia])->sum('cantidad');
        $maples = floor($totalHuevos / 30);
        $huevosSueltos = $totalHuevos % 30;

        // Población de aves para calcular eficiencia
        $poblacionAves = LoteAve::where('estado', '!=', 'Finalizado')->sum('cantidad_actual') ?: 100;
        $eficienciaPostura = min(100, round(($totalHuevos / $poblacionAves) * 100, 1));

        // 3. SUMINISTROS (Alimento y Agua)
        $despachosComida = Alimentacion::whereBetween('created_at', [$inicioDia, $finDia])->where('device_id', 'WEB_ACTION')->count();
        $gramosConsumidos = $despachosComida * 100;

        $despachosAgua = Hidratacion::whereBetween('created_at', [$inicioDia, $finDia])->where('device_id', 'WEB_ACTION')->count();
        $litrosConsumidos = $despachosAgua * 0.25;

        // 4. LISTA DE LECTURAS DEL DÍA (Para la tabla)
        $lecturasClima = TemperaturaHumedad::whereBetween('created_at', [$inicioDia, $finDia])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('reportes.diario', compact(
            'fecha',
            'tempPromedio',
            'tempMax',
            'tempMin',
            'humPromedio',
            'totalLecturas',
            'totalHuevos',
            'maples',
            'huevosSueltos',
            'poblacionAves',
            'eficienciaPostura',
            'gramosConsumidos',
            'litrosConsumidos',
            'lecturasClima'
        ));
    }

    // 2. HISTORIAL DE BÚSQUEDA Y FILTROS
    public function historial(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->subDays(15)->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', Carbon::now()->addDay()->format('Y-m-d'));
        $filtroEstado = $request->input('estado', 'todos');

        $inicio = Carbon::parse($fechaInicio)->startOfDay();
        $fin = Carbon::parse($fechaFin)->endOfDay();

        $query = TemperaturaHumedad::whereBetween('created_at', [$inicio, $fin])
            ->where('temperature', '>', 0);

        // Filtro por Estado Térmico
        if ($filtroEstado === 'calor') {
            $query->where('temperature', '>', 27);
        } elseif ($filtroEstado === 'frio') {
            $query->where('temperature', '<', 18);
        } elseif ($filtroEstado === 'optimo') {
            $query->whereBetween('temperature', [18, 27]);
        }

        $historialClima = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('reportes.historial', compact('historialClima', 'fechaInicio', 'fechaFin', 'filtroEstado'));
    }
}