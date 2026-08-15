<?php

use App\Http\Controllers\ESP32DataController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// 1. INICIO / DASHBOARD
Route::get('/', [ESP32DataController::class, 'index'])->name('granja.inicio');

// 2. MONITOREO - ALIMENTACIÓN (Dentro de la carpeta monitoreo)
Route::get('/monitoreo/alimentacion', function () {
    return view('monitoreo.alimentacion');
})->name('monitoreo.alimentacion');

// 3. CONFIGURACIÓN
Route::get('/configuracion', function () {
    return view('configuracion');
})->name('granja.config'); // Ajusta este nombre si tu sidebar pide otro

// 4. LOGIN
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/despachar-agua', [ESP32DataController::class, 'despacharAgua'])->name('agua.despachar');
Route::post('/recargar-agua', [ESP32DataController::class, 'recargarAgua'])->name('agua.recargar');