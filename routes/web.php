<?php

use App\Http\Controllers\ESP32DataController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoteAveController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SectorController;
// =========================================================================
// 1. RUTAS PÚBLICAS Y COMUNICACIÓN CON EL HARDWARE (Sin bloqueo de Login)
// =========================================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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

    

    // CRUD DE AVES / LOTES
    Route::get('/aves', [LoteAveController::class, 'index'])->name('aves.index');
    Route::get('/aves/nuevo', [LoteAveController::class, 'create'])->name('aves.create');
    Route::post('/aves/guardar', [LoteAveController::class, 'store'])->name('aves.store');
    Route::get('/aves/{id}/editar', [LoteAveController::class, 'edit'])->name('aves.edit');
    Route::put('/aves/{id}', [LoteAveController::class, 'update'])->name('aves.update');
    Route::delete('/aves/eliminar/{id}', [LoteAveController::class, 'destroy'])->name('aves.destroy');

    // MÓDULO DE REPORTES
    Route::get('/reportes/diario', [ReporteController::class, 'diario'])->name('reportes.diario');
    Route::get('/reportes/historial', [ReporteController::class, 'historial'])->name('reportes.historial');

    // SECTORES Y DISPOSITIVOS INDIVIDUALES
    Route::get('/monitoreo/alimentacion', [SectorController::class, 'alimentacion'])->name('monitoreo.alimentacion');
    Route::get('/monitoreo/hidratacion', [SectorController::class, 'hidratacion'])->name('monitoreo.hidratacion');
    Route::get('/monitoreo/temperatura', [SectorController::class, 'temperatura'])->name('monitoreo.temperatura');
    Route::get('/monitoreo/conteo-huevos', [SectorController::class, 'huevos'])->name('monitoreo.huevos');
});