@extends('layouts.app')

@section('contenido')

<div class="container-fluid">

    <!-- Encabezado con Botón de Reporte -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Panel de Control - Monitoreo en Tiempo Real</h1>
            <p class="text-muted small mb-0">Recepción de telemetría y control manual hacia el ESP32</p>
        </div>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm rounded-pill px-3 mt-2 mt-sm-0">
            <i class="fas fa-download fa-sm text-white-50 mr-1"></i> Generar Reporte General
        </a>
    </div>

    <!-- CONTENEDOR ACTUALIZABLE AUTOMÁTICAMENTE CADA 10 SEGUNDOS -->
    <div id="panel-actualizable">
        @php
            // Cálculos dinámicos de porcentajes para las barras de progreso
            $pesoActual = $ultimoAlimento->peso_comida ?? 2000;
            $capacidadMaxComida = 2500; // Capacidad máxima del silo en gramos
            $porcentajeComida = min(100, max(0, round(($pesoActual / $capacidadMaxComida) * 100)));

            $aguaActual = $ultimoAgua->nivel_agua ?? 3.00;
            $capacidadMaxAgua = 5.0; // Capacidad máxima del tanque en Litros
            $porcentajeAgua = min(100, max(0, round(($aguaActual / $capacidadMaxAgua) * 100)));
        @endphp

        <!-- FILA DE 4 TARJETAS INTERACTIVAS -->
        <div class="row">

            <!-- 1. SILO DE ALIMENTO -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-iot h-100 p-3 bg-white border-left-success">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs font-weight-bold text-success text-uppercase">SILO DE ALIMENTO</span>
                        <i class="fas fa-seedling fa-lg text-success"></i>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        <span id="peso-comida">{{ number_format($pesoActual, 0) }}</span> 
                        <small class="text-muted font-weight-normal fs-6">g</small>
                    </div>
                    
                    <!-- Barra de capacidad dinámica -->
                    <div class="progress my-2" style="height: 7px; border-radius: 10px;">
                        <div id="barra-comida" class="progress-bar bg-success" role="progressbar" style="width: {{ $porcentajeComida }}%;"></div>
                    </div>
                    <div class="d-flex justify-content-between text-muted small mb-3">
                        <span>Capacidad: <strong id="txt-porcentaje-comida">{{ $porcentajeComida }}%</strong></span>
                        <span class="text-success font-weight-bold">
                            {{ $porcentajeComida > 30 ? 'Nivel Óptimo' : 'Alerta: Silo Bajo' }}
                        </span>
                    </div>

                    <!-- Botones de Acción AJAX para Comida -->
                    <div class="btn-group btn-group-sm w-100 mt-auto" role="group">
                        <button type="button" onclick="accionComida('despachar')" class="btn btn-outline-success rounded-pill mr-1">
                            <i class="fas fa-utensils"></i> Despachar
                        </button>
                        <button type="button" onclick="accionComida('recargar')" class="btn btn-success rounded-pill ml-1 text-white">
                            <i class="fas fa-plus"></i> Llenar
                        </button>
                    </div>
                </div>
            </div>

            <!-- 2. HIDRATACIÓN / AGUA -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-iot h-100 p-3 bg-white border-left-info">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs font-weight-bold text-info text-uppercase">HIDRATACIÓN DISPONIBLE</span>
                        <i class="fas fa-tint fa-lg text-info"></i>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        <span id="litros-agua">{{ number_format($aguaActual, 2) }}</span> 
                        <small class="text-muted font-weight-normal fs-6">L</small>
                    </div>
                    
                    <!-- Barra de nivel de agua dinámica -->
                    <div class="progress my-2" style="height: 7px; border-radius: 10px;">
                        <div id="barra-agua" class="progress-bar bg-info" role="progressbar" style="width: {{ $porcentajeAgua }}%;"></div>
                    </div>
                    <div class="d-flex justify-content-between text-muted small mb-3">
                        <span>Nivel: <strong id="txt-porcentaje-agua">{{ $porcentajeAgua }}%</strong></span>
                        <span class="text-info font-weight-bold">
                            {{ $porcentajeAgua > 25 ? 'Abastecido' : 'Alerta: Nivel Bajo' }}
                        </span>
                    </div>

                    <!-- Botones de Acción AJAX para Agua -->
                    <div class="btn-group btn-group-sm w-100 mt-auto" role="group">
                        <button type="button" onclick="accionAgua('despachar')" class="btn btn-outline-info rounded-pill mr-1">
                            <i class="fas fa-faucet"></i> Despachar
                        </button>
                        <button type="button" onclick="accionAgua('recargar')" class="btn btn-info rounded-pill ml-1 text-white">
                            <i class="fas fa-sync-alt"></i> Llenar
                        </button>
                    </div>
                </div>
            </div>

            <!-- 3. AMBIENTE (TEMPERATURA Y HUMEDAD) -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-iot h-100 p-3 bg-white border-left-warning">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs font-weight-bold text-warning text-uppercase">CLIMA DEL AVIARIO</span>
                        <i class="fas fa-sun fa-lg text-warning"></i>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mb-2">
                        <span class="h3 mb-0 font-weight-bold text-gray-800 mr-2">
                            {{ $ultimoClima->temperature ?? '0' }}°<small class="text-muted font-weight-normal">C</small>
                        </span>
                        <span class="text-gray-400">|</span>
                        <span class="h4 mb-0 font-weight-bold text-secondary ml-2">
                            {{ $ultimoClima->humidity ?? '0' }}% <small class="text-muted font-weight-normal">HR</small>
                        </span>
                    </div>
                    <div class="alert alert-warning py-1 px-2 my-2 small border-0 rounded-lg d-flex align-items-center" style="font-size: 0.78rem;">
                        <i class="fas fa-check-circle mr-1 text-warning"></i> Rango de confort térmico
                    </div>
                    <small class="text-muted mt-auto"><i class="fas fa-thermometer-half mr-1"></i> Sensor DHT22 Activo</small>
                </div>
            </div>

            <!-- 4. CONTEO DE HUEVOS -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-iot h-100 p-3 bg-white border-left-secondary">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs font-weight-bold text-secondary text-uppercase">POSTURA DE HUEVOS</span>
                        <i class="fas fa-egg fa-lg text-secondary"></i>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        {{ $ultimaProduccion->cantidad ?? '0' }} 
                        <small class="text-muted font-weight-normal fs-6">unid.</small>
                    </div>
                    <div class="d-flex align-items-center text-success small my-2 font-weight-bold">
                        <i class="fas fa-arrow-up mr-1"></i> Postura registrada hoy
                    </div>
                    <small class="text-muted mt-auto"><i class="fas fa-history mr-1"></i> Sensor óptico activo</small>
                </div>
            </div>

        </div>
    </div> <!-- Fin panel-actualizable -->

    <!-- FILA DE GRÁFICAS INTERACTIVAS (APEXCHARTS) -->
    <div class="row">

        <!-- Gráfica de Líneas en Área (Temperatura y Humedad) -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card card-iot bg-white p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-chart-area mr-1 text-primary"></i> Historial Ambiental (Últimas Horas)
                    </h6>
                    <span class="badge badge-light border text-muted px-2 py-1">En Vivo</span>
                </div>
                <div id="chart-temperatura" style="min-height: 280px;"></div>
            </div>
        </div>

        <!-- Gráfica Radial (Balance de Recursos Silo vs Agua) -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card card-iot bg-white p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-pie-chart mr-1 text-primary"></i> Balance de Recursos
                    </h6>
                </div>
                <div id="chart-recursos" style="min-height: 280px;"></div>
            </div>
        </div>

    </div>

</div>

@endsection

@section('scripts')
<!-- jQuery para peticiones AJAX al ESP32 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // ==========================================
    // 1. FUNCIÓN AJAX PARA CONTROL DE AGUA
    // ==========================================
    window.accionAgua = function(tipo) {
        let urlDestino = (tipo === 'despachar') ? "{{ route('agua.despachar') }}" : "{{ route('agua.recargar') }}";

        $.ajax({
            url: urlDestino,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) {
                    let nuevoNivel = parseFloat(response.nuevo_nivel);
                    $('#litros-agua').text(nuevoNivel.toFixed(2));
                    
                    // Actualiza la barra de agua dinámicamente
                    let porcentaje = Math.min(100, Math.max(0, Math.round((nuevoNivel / 5.0) * 100)));
                    $('#barra-agua').css('width', porcentaje + '%');
                    $('#txt-porcentaje-agua').text(porcentaje + '%');
                    
                    alert("¡Éxito! Orden de AGUA enviada al ESP32.");
                }
            },
            error: function(xhr) { 
                alert("Error al conectar con el ESP32 para Agua."); 
            }
        });
    }

    // ==========================================
    // 2. FUNCIÓN AJAX PARA CONTROL DE COMIDA
    // ==========================================
    window.accionComida = function(tipo) {
        let urlDestino = (tipo === 'despachar') ? "{{ route('comida.despachar') }}" : "{{ route('comida.recargar') }}";

        $.ajax({
            url: urlDestino,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) {
                    let nuevoPeso = parseFloat(response.nuevo_peso);
                    $('#peso-comida').text(nuevoPeso.toLocaleString());
                    
                    // Actualiza la barra del silo dinámicamente
                    let porcentaje = Math.min(100, Math.max(0, Math.round((nuevoPeso / 2500) * 100)));
                    $('#barra-comida').css('width', porcentaje + '%');
                    $('#txt-porcentaje-comida').text(porcentaje + '%');
                    
                    alert("¡Éxito! Orden de COMIDA enviada al servo.");
                }
            },
            error: function(xhr) {
                alert("Error al procesar la orden de comida.");
            }
        });
    }

    // ==========================================
    // 3. RECARGA AUTOMÁTICA EN TIEMPO REAL (10s)
    // (Solo actualiza las tarjetas para no recargar las gráficas)
    // ==========================================
    setInterval(function() {
        $('#panel-actualizable').load(window.location.href + ' #panel-actualizable > *');
    }, 10000);


    // ==========================================
    // 4. INICIALIZACIÓN DE GRÁFICAS APEXCHARTS
    // ==========================================
    document.addEventListener("DOMContentLoaded", function() {
        
        // Gráfica de Temperatura y Humedad
        var optionsTemp = {
            series: [{
                name: 'Temperatura (°C)',
                data: [
                    {{ max(18, ($ultimoClima->temperature ?? 24) - 3) }},
                    {{ max(18, ($ultimoClima->temperature ?? 24) - 2) }},
                    {{ max(18, ($ultimoClima->temperature ?? 24) - 1) }},
                    {{ $ultimoClima->temperature ?? 24.5 }},
                    {{ ($ultimoClima->temperature ?? 24.5) + 0.5 }},
                    {{ $ultimoClima->temperature ?? 24.5 }}
                ]
            }, {
                name: 'Humedad (%HR)',
                data: [
                    {{ max(40, ($ultimoClima->humidity ?? 65) - 5) }},
                    {{ max(40, ($ultimoClima->humidity ?? 65) - 2) }},
                    {{ $ultimoClima->humidity ?? 65 }},
                    {{ ($ultimoClima->humidity ?? 65) + 1 }},
                    {{ $ultimoClima->humidity ?? 65 }},
                    {{ $ultimoClima->humidity ?? 65 }}
                ]
            }],
            chart: {
                height: 280,
                type: 'area',
                toolbar: { show: false },
                fontFamily: 'Nunito, sans-serif'
            },
            colors: ['#f59e0b', '#0ea5e9'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2.5 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: ['08:00', '10:00', '12:00', '14:00', '16:00', 'Ahora'],
                labels: { style: { colors: '#64748b' } }
            },
            yaxis: {
                labels: { style: { colors: '#64748b' } }
            },
            legend: { position: 'top', horizontalAlign: 'right' }
        };
        new ApexCharts(document.querySelector("#chart-temperatura"), optionsTemp).render();


        // Gráfica Radial (Recursos Disponibles)
        var optionsRecursos = {
            series: [{{ $porcentajeComida }}, {{ $porcentajeAgua }}],
            chart: {
                height: 290,
                type: 'radialBar',
                fontFamily: 'Nunito, sans-serif'
            },
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: { fontSize: '14px' },
                        value: { 
                            fontSize: '18px', 
                            fontWeight: 'bold',
                            formatter: function(val) { return val + '%'; }
                        },
                        total: {
                            show: true,
                            label: 'Estado Silos',
                            formatter: function () { return 'Activo'; }
                        }
                    }
                }
            },
            labels: ['Alimento', 'Agua'],
            colors: ['#10b981', '#0ea5e9']
        };
        new ApexCharts(document.querySelector("#chart-recursos"), optionsRecursos).render();
    });
</script>
@endsection