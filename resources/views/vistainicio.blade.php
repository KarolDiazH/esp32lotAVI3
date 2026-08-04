@extends('layouts.app')

@section('contenido')

<div class="container-fluid" id="panel-actualizable">
    <!-- Encabezado de la Página -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de Control - Monitoreo en Tiempo Real</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generar Reporte General
        </a>
    </div>

    <!-- Fila de Tarjetas (Cards) -->
    <div class="row">

        <!-- 1. ALIMENTACION -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                ALIMENTACIÓN</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $ultimoAlimento->weight_raw ?? '0' }} <small class="text-muted text-uppercase">g</small>
                            </div>
                            <small class="text-success font-weight-bold mt-1 d-block">
                                <i class="fas fa-arrow-up"></i> +8.5% vs ayer
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-leaf fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. HIDRATACION -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                HIDRATACIÓN</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $ultimoAgua->nivel_agua ?? '0.00' }} <small class="text-muted">L</small>
                            </div>
                            <small class="text-info font-weight-bold mt-1 d-block">
                                <i class="fas fa-arrow-up"></i> +5.2% vs ayer
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tint fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. TEMPERATURA Y HUMEDAD (ACTUALIZADA) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                AMBIENTE</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $ultimoClima->temperature ?? '0' }}<span class="small">°C</span>
                                <span class="mx-2 text-gray-300">|</span>
                                {{ $ultimoClima->humidity ?? '0' }}<span class="small">% HR</span>
                            </div>
                            <small class="text-warning font-weight-bold mt-1 d-block">
                                <i class="fas fa-thermometer-half"></i> Temp / <i class="fas fa-droplet"></i> Humedad
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cloud-sun fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                CONTEO DE HUEVOS</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $ultimaProduccion->cantidad ?? '0' }} <small class="text-muted text-uppercase">unid.</small>
                            </div>
                            <small class="text-primary font-weight-bold mt-1 d-block">
                                <i class="fas fa-history"></i> Actualizado hace poco
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-egg fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

   
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Historial de Consumo de Alimento (24h)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Estado de Recursos</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-success"></i> Alimento</span>
                        <span class="mr-2"><i class="fas fa-circle text-info"></i> Agua</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    setInterval(function() {
        console.log("Actualizando datos desde el servidor...");
    
        $('#panel-actualizable').load(window.location.href + ' #panel-actualizable > *');
    }, 10000);
</script>

@endsection