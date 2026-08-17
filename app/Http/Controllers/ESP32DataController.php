<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemperaturaHumedad;
use App\Models\ConteoHuevo;
use App\Models\Hidratacion; 
use App\Models\Alimentacion; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache; 

class ESP32DataController extends Controller
{
   
    public function index() 
    {
        $ultimoClima = TemperaturaHumedad::latest()->first();
        $ultimaProduccion = ConteoHuevo::latest()->first();
        $ultimoAgua = Hidratacion::latest()->first() ?? (object)['nivel_agua' => 3.00];
        $ultimoAlimento = Alimentacion::latest()->first() ?? (object)['peso_comida' => 2000];

        return view('vistainicio', compact('ultimoClima', 'ultimaProduccion', 'ultimoAlimento', 'ultimoAgua'));
    }

  
    public function store(Request $request)
    {
        $tokenEsperado = env('ESP32_TOKEN', 'mi_token_secreto_123');
        $tokenRecibido = $request->bearerToken();

        if ($tokenRecibido !== $tokenEsperado) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        try {
            // 1. Guardar Clima
            TemperaturaHumedad::create([
                'device_id'   => $request->device_id,
                'temperature' => $request->temp,
                'humidity'    => $request->hum,
            ]);

            // 2. Lógica de Huevos
            $peso = $request->peso;
            $cantidad = ($peso >= 9) ? floor($peso / 10) : 0;

            ConteoHuevo::create([
                'device_id'  => $request->device_id,
                'weight_raw' => $peso,
                'cantidad'   => $cantidad,
            ]);

            // 3. REVISAR ÓRDENES PENDIENTES (Agua y Comida)
            $ordenBomba = Cache::pull('activar_bomba', false);
            $ordenServo = Cache::pull('activar_servo', false); // Señal para el PCA9685

            if ($ordenServo) Log::info(">>> [API] Enviando orden de COMIDA al ESP32.");
            if ($ordenBomba) Log::info(">>> [API] Enviando orden de AGUA al ESP32.");

            return response()->json([
                'status'           => 'success',
                'huevos'           => $cantidad,
                'despachar_agua'   => (bool)$ordenBomba,
                'despachar_comida' => (bool)$ordenServo
            ], 201);

        } catch (\Exception $e) {
            Log::error("Error API: " . $e->getMessage());
            return response()->json(['error' => 'Error interno'], 500);
        }
    }

    /**
     * WEB: Acción para DESPACHAR COMIDA (Servo)
     */
    public function despacharComida(Request $request)
    {
        try {
            $actual = Alimentacion::latest()->first();
            $pesoActual = $actual ? $actual->peso_comida : 2000;

            if ($pesoActual > 0) {
                // Restamos 100g por cada apertura de compuerta
                $nuevoPeso = max(0, $pesoActual - 100); 
                
                Alimentacion::create([
                    'device_id'   => 'WEB_ACTION',
                    'peso_comida' => $nuevoPeso
                ]);

                // ACTIVAMOS SEÑAL EN CACHE PARA EL SERVO
                Cache::put('activar_servo', true, 60);

                return response()->json(['success' => true, 'nuevo_peso' => $nuevoPeso]);
            }
            return response()->json(['success' => false, 'mensaje' => 'Silo vacío']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * WEB: Acción para RECARGAR COMIDA a 2kg
     */
    public function recargarComida()
    {
        Alimentacion::create([
            'device_id'   => 'SISTEMA_RECARGA',
            'peso_comida' => 2000 
        ]);
        return response()->json(['success' => true, 'nuevo_peso' => 2000]);
    }

    /**
     * WEB: Acción para DESPACHAR AGUA (Bomba)
     */
    public function despacharAgua(Request $request)
    {
        try {
            $actual = Hidratacion::latest()->first();
            $nivelActual = $actual ? $actual->nivel_agua : 3.00;

            if ($nivelActual > 0) {
                $nuevoNivel = max(0, $nivelActual - 0.25); 
                Hidratacion::create(['device_id' => 'WEB_ACTION', 'nivel_agua' => $nuevoNivel]);
                Cache::put('activar_bomba', true, 60); 

                return response()->json(['success' => true, 'nuevo_nivel' => $nuevoNivel]);
            }
            return response()->json(['success' => false, 'mensaje' => 'Tanque vacío']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * WEB: Acción para RECARGAR AGUA a 3L
     */
    public function recargarAgua()
    {
        Hidratacion::create(['device_id' => 'SISTEMA_RECARGA', 'nivel_agua' => 3.00]);
        return response()->json(['success' => true, 'nuevo_nivel' => 3.00]);
    }
}