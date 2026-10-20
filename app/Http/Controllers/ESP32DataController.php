<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemperaturaHumedad;
use App\Models\ConteoHuevo;
use App\Models\Hidratacion;
use App\Models\Alimentacion;
use App\Models\Dispositivo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ESP32DataController extends Controller
{
    public function index()
    {
        $ultimoClima = TemperaturaHumedad::latest()->first();
        $ultimaProduccion = (object)['cantidad' => (int) ConteoHuevo::whereDate('created_at', today())->sum('cantidad')];
        $ultimoAgua = Hidratacion::latest()->first() ?? (object)['nivel_agua' => 3.00];
        $ultimoAlimento = Alimentacion::latest()->first() ?? (object)['peso_comida' => 0];

        return view('vistainicio', compact('ultimoClima', 'ultimaProduccion', 'ultimoAlimento', 'ultimoAgua'));
    }

    public function store(Request $request)
    {
        $tokenEsperado = env('ESP32_TOKEN', 'mi_token_secreto_123');
        if ($request->bearerToken() !== $tokenEsperado) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        try {
            $deviceId = $request->input('device_id', 'ESP32_GALPON_PROD');

            // 0. Vincular al dispositivo (se crea solo la primera vez)
            $dispositivo = Dispositivo::firstOrCreate(
                ['device_id' => $deviceId],
                ['nombre' => $deviceId, 'estado' => 'activo']
            );
            $dispositivo->update(['last_seen_at' => now()]);
            $base = ['device_id' => $deviceId, 'dispositivo_id' => $dispositivo->id];

            // 1. CLIMA (solo si el DHT11 leyó bien)
            $dhtOk = $request->boolean('status_dht', true);
            if ($dhtOk && $request->filled('temp') && $request->filled('hum')) {
                TemperaturaHumedad::create($base + [
                    'temperature' => (float) $request->temp,
                    'humidity'    => (float) $request->hum,
                ]);
            }

            // 2. HUEVOS: el ESP32 manda solo los huevos NUEVOS desde el último envío exitoso
            $huevosNuevos = (int) $request->input('huevos_nuevos', 0);
            if ($huevosNuevos > 0) {
                ConteoHuevo::create($base + [
                    'weight_raw' => 0,
                    'cantidad'   => $huevosNuevos,
                ]);
            }

            // 3. ALIMENTACIÓN (peso del plato en gramos). Se guarda si cambió o pasó 1 min
            if ($request->filled('peso') && $request->boolean('status_hx711', true)) {
                $peso = round((float) $request->peso, 1);
                $ult  = Alimentacion::where('device_id', $deviceId)->latest('id')->first();
                if (!$ult || abs($ult->peso_comida - $peso) >= 2 || $ult->created_at->diffInSeconds(now()) >= 60) {
                    Alimentacion::create($base + [
                        'peso_comida'      => $peso,
                        'esta_dispensando' => $request->boolean('esta_dispensando'),
                        'contador_diario'  => (int) $request->input('contador_comida', 0),
                    ]);
                }
            }

            // 4. HIDRATACIÓN (flotadores). Nivel estimado: lleno=5L, medio=3L, bajo=1L
            if ($request->has('flotador_alto') || $request->has('flotador_bajo')) {
                $alto = $request->boolean('flotador_alto');
                $bajo = $request->boolean('flotador_bajo');
                $nivel = $alto ? 5.0 : ($bajo ? 1.0 : 3.0);
                $ult = Hidratacion::where('device_id', $deviceId)->latest('id')->first();
                if (!$ult || (float) $ult->nivel_agua !== $nivel
                    || (bool) $ult->bomba_encendida !== $request->boolean('bomba_encendida')
                    || $ult->created_at->diffInSeconds(now()) >= 60) {
                    Hidratacion::create($base + [
                        'nivel_agua'      => $nivel,
                        'flotador_bajo'   => $bajo,
                        'flotador_alto'   => $alto,
                        'bomba_encendida' => $request->boolean('bomba_encendida'),
                    ]);
                }
            }

            // 5. ÓRDENES PENDIENTES desde el dashboard
            $ordenBomba = Cache::pull('activar_bomba', false);
            $ordenServo = Cache::pull('activar_servo', false);
            if ($ordenServo) Log::info(">>> [API] Enviando orden de COMIDA al ESP32.");
            if ($ordenBomba) Log::info(">>> [API] Enviando orden de AGUA al ESP32.");

            return response()->json([
                'status'           => 'success',
                'despachar_agua'   => (bool) $ordenBomba,
                'despachar_comida' => (bool) $ordenServo,
            ], 201);

        } catch (\Exception $e) {
            Log::error("Error API: " . $e->getMessage());
            return response()->json(['error' => 'Error interno'], 500);
        }
    }

    public function despacharComida(Request $request)
    {
        try {
            $actual = Alimentacion::latest('id')->first();
            $pesoActual = $actual ? $actual->peso_comida : 0;

            // Registra la acción manual (para reportes) SIN alterar el peso real medido
            Alimentacion::create(['device_id' => 'WEB_ACTION', 'peso_comida' => $pesoActual]);
            Cache::put('activar_servo', true, 60);

            return response()->json(['success' => true, 'nuevo_peso' => $pesoActual]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function recargarComida()
    {
        Alimentacion::create([
            'device_id'   => 'SISTEMA_RECARGA',
            'peso_comida' => 2000
        ]);
        return response()->json(['success' => true, 'nuevo_peso' => 2000]);
    }

    public function despacharAgua(Request $request)
    {
        try {
            $actual = Hidratacion::latest('id')->first();
            $nivelActual = $actual ? $actual->nivel_agua : 3.00;

            Hidratacion::create(['device_id' => 'WEB_ACTION', 'nivel_agua' => $nivelActual]);
            Cache::put('activar_bomba', true, 60);

            return response()->json(['success' => true, 'nuevo_nivel' => $nivelActual]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function recargarAgua()
    {
        Hidratacion::create(['device_id' => 'SISTEMA_RECARGA', 'nivel_agua' => 3.00]);
        return response()->json(['success' => true, 'nuevo_nivel' => 3.00]);
    }
}