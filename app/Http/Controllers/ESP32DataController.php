<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemperaturaHumedad;
use App\Models\ConteoHuevo;
use App\Models\Hidratacion; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache; // <--- Fundamental para la bomba

class ESP32DataController extends Controller
{
    /**
     * Muestra el Dashboard (Frontend)
     */
    public function index() 
    {
        $ultimoClima = TemperaturaHumedad::latest()->first();
        $ultimaProduccion = ConteoHuevo::latest()->first();
        $ultimoAlimento = $ultimaProduccion; 

        // Obtenemos el nivel de agua
        $ultimoAgua = Hidratacion::latest()->first();
        
        // Si no hay datos, inicializamos en 3.00L
        if (!$ultimoAgua) {
            $ultimoAgua = (object)['nivel_agua' => 3.00];
        }

        return view('vistainicio', compact('ultimoClima', 'ultimaProduccion', 'ultimoAlimento', 'ultimoAgua'));
    }

    /**
     * API: Recibe datos del ESP32 y le entrega la orden de la bomba
     */
    public function store(Request $request)
    {
        // 1. Verificación de Seguridad
        $tokenEsperado = env('ESP32_TOKEN', 'mi_token_secreto_123');
        $tokenRecibido = $request->bearerToken();

        if ($tokenRecibido !== $tokenEsperado) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        try {
            // 2. Guardar Clima
            TemperaturaHumedad::create([
                'device_id'   => $request->device_id,
                'temperature' => $request->temp,
                'humidity'    => $request->hum,
            ]);

            // 3. Lógica de Peso y Huevos
            $peso = $request->peso;
            $cantidad = ($peso >= 9) ? floor($peso / 10) : 0;

            ConteoHuevo::create([
                'device_id'  => $request->device_id,
                'weight_raw' => $peso,
                'cantidad'   => $cantidad,
            ]);

            // 4. REVISAR SI HAY ORDEN DE BOMBEO PENDIENTE
            // pull() obtiene el valor y lo ELIMINA para que no se repita el bombeo
            $ordenBomba = Cache::pull('activar_bomba', false);

            if ($ordenBomba) {
                Log::info(">>> [API] El ESP32 se conectó. Enviando señal de ACTIVAR BOMBA.");
            }

            return response()->json([
                'status'    => 'success',
                'huevos'    => $cantidad,
                'despachar' => (bool)$ordenBomba // Enviamos true o false
            ], 201);

        } catch (\Exception $e) {
            Log::error("Error en store API: " . $e->getMessage());
            return response()->json(['error' => 'Error interno'], 500);
        }
    }

    /**
     * WEB: Acción al presionar el botón "Despachar"
     */
    public function despacharAgua(Request $request)
    {
        try {
            Log::info(">>> [WEB] Botón presionado. Generando orden de despacho.");

            $actual = Hidratacion::latest()->first();
            $nivelActual = $actual ? $actual->nivel_agua : 3.00;

            if ($nivelActual > 0) {
                // Restamos 0.25L
                $nuevoNivel = max(0, $nivelActual - 0.25); 
                
                Hidratacion::create([
                    'device_id' => 'WEB_USER_ACTION',
                    'nivel_agua' => $nuevoNivel
                ]);

                // PONEMOS LA ORDEN EN CACHE (esperando al ESP32)
                Cache::put('activar_bomba', true, 60); 

                return response()->json([
                    'success' => true, 
                    'nuevo_nivel' => $nuevoNivel,
                    'mensaje' => 'Orden enviada. El ESP32 activará el relé en su próxima actualización.'
                ]);
            }

            return response()->json(['success' => false, 'mensaje' => 'Tanque vacío'], 400);

        } catch (\Exception $e) {
            Log::error("Error en despacharAgua: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * WEB: Acción para resetear a 3 Litros
     */
    public function recargarAgua()
    {
        try {
            Log::info(">>> [WEB] Tanque recargado a 3 Litros.");
            Hidratacion::create([
                'device_id' => 'SISTEMA_RECARGA',
                'nivel_agua' => 3.00 
            ]);
            return response()->json(['success' => true, 'nuevo_nivel' => 3.00]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}