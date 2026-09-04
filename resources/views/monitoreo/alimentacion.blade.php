@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    <!-- Encabezado con Estado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Sector 1: Control de Alimentación y Silo</h1>
            <p class="text-muted small mb-0">Gestión de la báscula HX711, pesaje en tiempo real y dosificación por servomotor</p>
        </div>
        <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
            <span class="badge badge-success px-3 py-2 text-uppercase shadow-sm">
                <i class="fas fa-check-circle mr-1"></i> Servo PCA9685: Activo
            </span>
        </div>
    </div>

    <!-- 4 TARJETAS DE MÉTRICAS DEL SILO -->
    <div class="row mb-4">

        <!-- 1. PESO ACTUAL EN EL SILO -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-success shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-success text-uppercase">Nivel del Silo (HX711)</span>
                    <i class="fas fa-weight text-success fa-lg"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">
                    <span id="txt-peso">{{ number_format($peso) }}</span> <small class="text-muted fs-6">g</small>
                </h2>
                <div class="progress my-2" style="height: 7px; border-radius: 10px;">
                    <div id="barra-silo" class="progress-bar bg-success" role="progressbar" style="width: {{ $porcentaje }}%;"></div>
                </div>
                <div class="d-flex justify-content-between small text-muted font-weight-bold">
                    <span>Capacidad: <strong id="txt-porcentaje">{{ $porcentaje }}%</strong></span>
                    <span class="{{ $porcentaje > 25 ? 'text-success' : 'text-danger' }}">
                        {{ $porcentaje > 25 ? 'Abastecido' : 'Alerta: Silo Bajo' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. CONSUMO DEL DÍA -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-primary shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-primary text-uppercase">Consumo Hoy</span>
                    <i class="fas fa-utensils text-primary fa-lg"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">
                    <span id="txt-consumo">{{ number_format($consumoHoyGramos) }}</span> <small class="text-muted fs-6">g</small>
                </h2>
                <div class="small text-muted font-weight-bold">
                    <i class="fas fa-layer-group text-primary mr-1"></i> {{ $despachosHoy }} Raciones entregadas
                </div>
                <div class="small text-primary mt-1 font-weight-bold">
                    Promedio: {{ $gramosPorAve }} g / ave
                </div>
            </div>
        </div>

        <!-- 3. POBLACIÓN ACTIVA -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-warning shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-warning text-uppercase">Población en Alimentación</span>
                    <i class="fas fa-kiwi-bird text-warning fa-lg"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">{{ number_format($poblacionAves) }} <small class="text-muted fs-6">aves</small></h2>
                <div class="small text-muted font-weight-bold">Ración recomendada: 25-30g/día</div>
                <div class="small text-muted mt-1">Lotes en producción activa</div>
            </div>
        </div>

        <!-- 4. DOSIFICADOR (COMPUERTA) -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-info shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-info text-uppercase">Dosificador Mecánico</span>
                    <i class="fas fa-cogs text-info fa-lg"></i>
                </div>
                <h3 class="font-weight-bold text-dark my-1">100 <small class="text-muted fs-6">g / ciclo</small></h3>
                <div class="small text-muted font-weight-bold">Servo: Ángulo 90° (1 seg)</div>
                <div class="small text-success mt-1 font-weight-bold">
                    <i class="fas fa-bolt mr-1"></i> Señal en línea
                </div>
            </div>
        </div>

    </div>

    <!-- SECCIÓN DE ACCIONES MANUALES Y GRÁFICA -->
    <div class="row mb-4">

        <!-- PANEL DE CONTROL MANUAL (BOTONES AJAX) -->
        <div class="col-lg-4 mb-4">
            <div class="card card-iot bg-white shadow-sm p-4 h-100">
                <h6 class="font-weight-bold text-dark mb-3">
                    <i class="fas fa-hand-pointer text-success mr-2"></i> Accionamiento Manual
                </h6>
                <p class="text-muted small mb-4">Envía una orden inmediata a la cola de comandos de Laravel para que el ESP32 accione la compuerta del servo.</p>

                <div class="d-grid gap-3">
                    <!-- Botón Despachar Ración -->
                    <button type="button" onclick="despacharAlimento()" id="btn-despachar" class="btn btn-success btn-block py-3 rounded-pill shadow-sm font-weight-bold mb-3">
                        <i class="fas fa-utensils mr-2"></i> Despachar Ración (100g)
                    </button>

                    <!-- Botón Recargar Silo -->
                    <button type="button" onclick="recargarSilo()" id="btn-recargar" class="btn btn-outline-success btn-block py-3 rounded-pill font-weight-bold">
                        <i class="fas fa-fill-drip mr-2"></i> Llenar Silo (2,500g)
                    </button>
                </div>

                <div class="alert alert-light border small text-muted mt-4 mb-0 rounded-3">
                    <i class="fas fa-info-circle text-primary mr-1"></i> Al despachar, el ESP32 abrirá la compuerta 90° durante 1 segundo en su siguiente ciclo.
                </div>
            </div>
        </div>

        <!-- GRÁFICA RADIAL Y BALANCE -->
        <div class="col-lg-8 mb-4">
            <div class="card card-iot bg-white shadow-sm p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-chart-pie text-primary mr-2"></i> Estado de Carga del Silo de Alimento
                    </h6>
                    <span class="badge badge-light border">Capacidad Max: 2.5 kg</span>
                </div>
                <div id="chart-silo" style="min-height: 260px;"></div>
            </div>
        </div>

    </div>

    <!-- TABLA DE AUDITORÍA Y REGISTRO DE ALIMENTACIÓN -->
    <div class="card card-iot bg-white shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history mr-1"></i> Registro Histórico de Despachos y Pesaje
            </h6>
            <span class="badge badge-success px-3 py-1">Últimos Eventos</span>
        </div>
        <div class="card-body p-4 pt-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="border-0">Hora / Fecha</th>
                            <th class="border-0">Origen de Orden</th>
                            <th class="border-0">Peso Registrado</th>
                            <th class="border-0">Estado del Silo</th>
                            <th class="border-0 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-alimentacion">
                        @forelse($historial as $item)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $item->created_at ? $item->created_at->format('d/m/Y H:i:s') : 'Reciente' }}</td>
                            <td>
                                @if($item->device_id == 'WEB_ACTION')
                                    <span class="badge badge-primary px-2 py-1"><i class="fas fa-mouse mr-1"></i> Manual Web</span>
                                @elseif($item->device_id == 'SISTEMA_RECARGA')
                                    <span class="badge badge-info px-2 py-1"><i class="fas fa-sync mr-1"></i> Recarga</span>
                                @else
                                    <span class="badge badge-light border text-muted"><i class="fas fa-microchip mr-1"></i> {{ $item->device_id ?? 'ESP32_AUTOMATICO' }}</span>
                                @endif
                            </td>
                            <td class="font-weight-bold text-success">{{ number_format($item->peso_comida) }} g</td>
                            <td>
                                @if($item->peso_comida > 500)
                                    <span class="badge badge-success px-3 py-1">Nivel Normal</span>
                                @else
                                    <span class="badge badge-danger px-3 py-1">Nivel Bajo</span>
                                @endif
                            </td>
                            <td class="text-center text-muted small">
                                <i class="fas fa-check text-success"></i> Procesado
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No hay registros de alimentación aún.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // ============================================================
    // 1. FUNCIÓN AJAX: DESPACHAR COMIDA (ORDEN AL SERVO)
    // ============================================================
    function despacharAlimento() {
        let btn = $('#btn-despachar');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Enviando orden...');

        $.ajax({
            url: "{{ route('comida.despachar') }}",
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-utensils mr-2"></i> Despachar Ración (100g)');
                
                if (res.success) {
                    let nuevoPeso = res.nuevo_peso;
                    $('#txt-peso').text(nuevoPeso.toLocaleString());
                    
                    // Actualizar barra de progreso
                    let nuevoPorcentaje = Math.min(100, Math.max(0, Math.round((nuevoPeso / 2500) * 100)));
                    $('#barra-silo').css('width', nuevoPorcentaje + '%');
                    $('#txt-porcentaje').text(nuevoPorcentaje + '%');

                    // Actualizar gráfica radial
                    chartSilo.updateSeries([nuevoPorcentaje]);

                    alert("¡Éxito! Orden de 100g enviada. El servo abrirá la compuerta.");
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fas fa-utensils mr-2"></i> Despachar Ración (100g)');
                alert("Error al comunicar la orden de comida.");
            }
        });
    }

    // ============================================================
    // 2. FUNCIÓN AJAX: RECARGAR SILO COMPLETO
    // ============================================================
    function recargarSilo() {
        let btn = $('#btn-recargar');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Recargando...');

        $.ajax({
            url: "{{ route('comida.recargar') }}",
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-fill-drip mr-2"></i> Llenar Silo (2,500g)');
                
                if (res.success) {
                    $('#txt-peso').text('2,500');
                    $('#barra-silo').css('width', '100%');
                    $('#txt-porcentaje').text('100%');
                    chartSilo.updateSeries([100]);

                    alert("¡Silo recargado al 100%!");
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fas fa-fill-drip mr-2"></i> Llenar Silo (2,500g)');
                alert("Error al recargar el silo.");
            }
        });
    }

    // ============================================================
    // 3. GRÁFICA RADIAL DE APEXCHARTS
    // ============================================================
    var optionsSilo = {
        series: [{{ $porcentaje }}],
        chart: {
            height: 270,
            type: 'radialBar',
            fontFamily: 'Nunito, sans-serif'
        },
        plotOptions: {
            radialBar: {
                startAngle: -135,
                endAngle: 135,
                dataLabels: {
                    name: {
                        fontSize: '16px',
                        color: '#64748b',
                        offsetY: 10
                    },
                    value: {
                        offsetY: -15,
                        fontSize: '24px',
                        fontWeight: 'bold',
                        formatter: function (val) {
                            return val + "%";
                        }
                    }
                }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                shadeIntensity: 0.15,
                inverseColors: false,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 50, 65, 91]
            },
        },
        stroke: { dashArray: 4 },
        labels: ['Capacidad Silo'],
        colors: ['#2d6a4f']
    };

    var chartSilo = new ApexCharts(document.querySelector("#chart-silo"), optionsSilo);
    chartSilo.render();
</script>
@endsection