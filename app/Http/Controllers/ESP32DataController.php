<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemperaturaHumedad;
use App\Models\ConteoHuevo;
use Illuminate\Support\Facades\Log;

class ESP32DataController extends Controller
{
    /**
     * Muestra el Dashboard (Frontend)
     */
public function index() 
{
    $ultimoClima = TemperaturaHumedad::latest()->first();
    $ultimaProduccion = ConteoHuevo::latest()->first();
    
    // Usamos la misma producción para alimentar la tarjeta de alimentación
    $ultimoAlimento = $ultimaProduccion; 

    return view('vistainicio', compact('ultimoClima', 'ultimaProduccion', 'ultimoAlimento'));
}
    /**
     * Recibe y procesa los datos enviados por el ESP32 (API)
     */
    public function store(Request $request)
    {
        // 1. Verificación de seguridad (Token en Header Authorization)
        $tokenEsperado = env('ESP32_TOKEN', 'mi_token_secreto_123');
        $tokenRecibido = $request->bearerToken();

        if ($tokenRecibido !== $tokenEsperado) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        try {
            // 2. Guardar en Tabla de Clima (Temperatura y Humedad)
            TemperaturaHumedad::create([
                'device_id'   => $request->device_id,
                'temperature' => $request->temp,
                'humidity'    => $request->hum,
            ]);

            // 3. Lógica de Conteo de Huevos (10g por huevo)
            $peso = $request->peso;
            $cantidad = ($peso >= 9) ? floor($peso / 10) : 0;

            // 4. Guardar en Tabla de Huevos (Producción)
            ConteoHuevo::create([
                'device_id'  => $request->device_id,
                'weight_raw' => $peso,
                'cantidad'   => $cantidad,
            ]);

            return response()->json([
                'status'  => 'success',
                'mensaje' => 'Datos procesados',
                'huevos'  => $cantidad
            ], 201);

        } catch (\Exception $e) {
            Log::error("Error procesando datos ESP32: " . $e->getMessage());
            return response()->json(['error' => 'Error interno en el servidor'], 500);
        }
    }
}