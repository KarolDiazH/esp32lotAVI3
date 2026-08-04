@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <!-- Encabezado con refresco automático cada 30 seg opcional -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Monitoreo de Producción y Alimentación</h1>
        <span class="badge badge-info shadow-sm">Actualizado: {{ now()->format('H:i:s') }}</span>
    </div>

    <!-- Fila de Tarjetas de Estado Actual -->
    <div class="row">
        <!-- Cantidad de Huevos Detectados -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 bg-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Huevos en Bandeja (Calculado)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ultimaProduccion->cantidad ?? 0 }} Unidades</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-egg fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Temperatura Actual -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 bg-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Temperatura Ambiente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ultimoClima->temperature ?? 0 }}°C</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-thermometer-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Humedad Actual -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 bg-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Humedad</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ultimoClima->humidity ?? 0 }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tint fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tarjeta de Control Rápido -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow border-left-success bg-white">
                <div class="card-body">
                    <h6 class="font-weight-bold text-success">Acción Manual</h6>
                    <p class="small text-muted">Ajuste de báscula o activación del dispensador remoto.</p>
                    <div class="alert alert-secondary py-2 small">Peso Bruto: {{ $ultimaProduccion->weight_raw ?? 0 }} g</div>
                    <button class="btn btn-success btn-block shadow-sm">Dispensar Alimento Ahora</button>
                </div>
            </div>
        </div>

        <!-- Tabla de Historial de Producción -->
        <div class="col-lg-8">
            <div class="card shadow mb-4 bg-white">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Historial de Conteo (Báscula)</h6>
                    <i class="fas fa-history text-gray-400"></i>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Fecha y Hora</th>
                                    <th>Peso Detectado (g)</th>
                                    <th>Cantidad Huevos</th>
                                    <th>Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($historial as $dato)
                                <tr>
                                    <td>{{ $dato->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $dato->weight_raw }} g</td>
                                    <td class="font-weight-bold text-primary">{{ $dato->cantidad }}</td>
                                    <td>
                                        @if($dato->cantidad > 0)
                                            <span class="badge badge-success">Producción Detectada</span>
                                        @else
                                            <span class="badge badge-secondary">Bandeja Vacía</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No hay lecturas registradas del ESP32</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection