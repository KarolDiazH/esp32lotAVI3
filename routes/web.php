<?php

use App\Http\Controllers\ESP32DataController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// =========================================================================
// 1. RUTAS PÚBLICAS Y COMUNICACIÓN CON EL HARDWARE (Sin bloqueo de Login)
// =========================================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// AQUÍ ESTÁ LA RUTA QUE CONECTA THONNY (ESP32) CON LA FUNCIÓN STORE

Route::post('/v1/sensor-update', [ESP32DataController::class, 'store']);

// =========================================================================
// 2. RUTAS PRIVADAS DEL SISTEMA (Solo personas logueadas en el navegador)
// =========================================================================
Route::middleware(['auth'])->group(function () {

    // Inicio / Dashboard Principal
    Route::get('/', [ESP32DataController::class, 'index'])->name('granja.inicio');

    // Monitoreo
    Route::get('/monitoreo/alimentacion', function () {
        return view('monitoreo.alimentacion');
    })->name('monitoreo.alimentacion');

    // Configuración
    Route::get('/configuracion', function () {
        return view('configuracion');
    })->name('granja.config');

    // Gestión de Usuarios (Solo Admin)
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/nuevo', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios/guardar', [UserController::class, 'store'])->name('usuarios.store');
    Route::delete('/usuarios/eliminar/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');
});