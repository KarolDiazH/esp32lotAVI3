@extends('layouts.app')

@section('contenido')

<div class="container-fluid">
    <!-- El div actualizable solo debe envolver las tarjetas y gráficos, NO el script -->
    <div id="panel-actualizable">
        <!-- Encabezado de la Página -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Panel de Control - Monitoreo en Tiempo Real</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Generar Reporte General
            </a>
        </div>

        <!-- Fila de Tarjetas (Cards) -->
        <div class="row">


    <!-- 1. ALIMENTACIÓN -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        SILO DE ALIMENTO</div>
                    <div class="h5 mb-2 font-weight-bold text-gray-800">
                        <!-- Añadimos ID peso-comida para actualizarlo con JS -->
                        <span id="peso-comida">{{ number_format($ultimoAlimento->peso_comida ?? 2000, 0) }}</span> 
                        <small class="text-muted text-uppercase">g</small>
                    </div>
                    
                    <!-- Botones de Acción para Comida -->
                    <div class="btn-group btn-group-sm w-100" role="group">
                        <button type="button" onclick="accionComida('despachar')" class="btn btn-success">
                            <i class="fas fa-box-open"></i> Despachar
                        </button>
                        <button type="button" onclick="accionComida('recargar')" class="btn btn-outline-success">
                            <i class="fas fa-fill-drip"></i> Llenar
                        </button>
                    </div>
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
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">HIDRATACIÓN DISPONIBLE</div>
                                <div class="h5 mb-2 font-weight-bold text-gray-800">
                                    <span id="litros-agua">{{ number_format($ultimoAgua->nivel_agua ?? 3.00, 2) }}</span> L
                                </div>
                                <!-- Botones de Acción -->
                                <div class="btn-group btn-group-sm w-100" role="group">
                                    <button type="button" onclick="accionAgua('despachar')" class="btn btn-info">
                                        <i class="fas fa-faucet"></i> Despachar
                                    </button>
                                    <button type="button" onclick="accionAgua('recargar')" class="btn btn-outline-info">
                                        <i class="fas fa-sync"></i> Llenar
                                    </button>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tint fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. TEMPERATURA Y HUMEDAD -->
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

            <!-- 4. CONTEO DE HUEVOS -->
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

        <!-- Fila de Gráficas -->
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
    </div> <!-- Fin panel-actualizable -->
</div>

<!-- SCRIPTS (FUERA DEL DIV QUE SE RECARGA) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // --- FUNCIÓN PARA AGUA ---
    window.accionAgua = function(tipo) {
        console.log("Clic detectado para Agua: " + tipo);
        let urlDestino = (tipo === 'despachar') ? "{{ route('agua.despachar') }}" : "{{ route('agua.recargar') }}";

        $.ajax({
            url: urlDestino,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) {
                    $('#litros-agua').text(response.nuevo_nivel.toFixed(2));
                    alert("¡Éxito! Orden de AGUA enviada.");
                }
            },
            error: function(xhr) { alert("Error al conectar con el servidor."); }
        });
    }

    // --- FUNCIÓN PARA COMIDA (NUEVA) ---
    window.accionComida = function(tipo) {
        console.log("Clic detectado para Comida: " + tipo);
        
        // Estas rutas deben estar en tu routes/web.php
        let urlDestino = (tipo === 'despachar') ? "{{ route('comida.despachar') }}" : "{{ route('comida.recargar') }}";

        $.ajax({
            url: urlDestino,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                console.log("Servidor respondió:", response);
                if(response.success) {
                    // Actualizamos el número de gramos en la tarjeta
                    $('#peso-comida').text(response.nuevo_peso);
                    alert("¡Éxito! Orden de COMIDA enviada al servo.");
                }
            },
            error: function(xhr) {
                console.error("Error:", xhr.responseText);
                alert("Error al procesar la comida.");
            }
        });
    }

    // Actualización automática cada 10 segundos
    setInterval(function() {
        $('#panel-actualizable').load(window.location.href + ' #panel-actualizable > *');
    }, 10000);
</script>

@endsection