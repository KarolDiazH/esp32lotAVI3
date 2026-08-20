<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ESP32DataController;

Route::get('/user', function (Request $request) {return $request->user();})->middleware('auth:sanctum');
Route::post('/v1/sensor-update', [ESP32DataController::class, 'store']);

Route::post('/v1/despachar-agua', [ESP32DataController::class, 'despacharAgua'])->name('agua.despachar');
Route::post('/v1/recargar-agua', [ESP32DataController::class, 'recargarAgua'])->name('agua.recargar');
Route::post('/v1/despachar-comida', [ESP32DataController::class, 'despacharComida'])->name('comida.despachar');
Route::post('/v1/recargar-comida', [ESP32DataController::class, 'recargarComida'])->name('comida.recargar');