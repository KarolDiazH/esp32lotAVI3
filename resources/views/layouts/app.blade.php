<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistema de Control y Monitoreo - Granja Avícola</title>

    <!-- Fuentes y FontAwesome -->
    <link href="{{ asset('startbootstrap/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos SB Admin 2 -->
    <link href="{{ asset('startbootstrap/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Librería de Gráficas Modernas (ApexCharts) -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        body {
            font-family: 'Nunito', sans-serif !important;
            background-color: #f8fafc;
        }

        /* Degradado verde agrícola para el Sidebar */
        .bg-gradient-primary {
            background-color: #143d2b !important;
            background-image: linear-gradient(180deg, #0d281c 0%, #1b4332 50%, #2d6a4f 100%) !important;
            background-size: cover;
        }

        .sidebar .nav-item .nav-link {
            padding: 0.85rem 1rem;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #2d6a4f !important;
            border-color: #2d6a4f !important;
        }

        .btn-primary:hover {
            background-color: #1b4332 !important;
            border-color: #1b4332 !important;
        }

        .text-primary {
            color: #2d6a4f !important;
        }

        /* Tarjetas estilizadas con sombra suave */
        .card-iot {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card-iot:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        /* Punto verde pulsante (ESP32 en línea) */
        .pulse-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 1.6s infinite;
        }

        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Logo -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center py-4" href="{{ route('granja.inicio') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-kiwi-bird fa-2x"></i>
                </div>
                <div class="sidebar-brand-text mx-2 text-capitalize">Granja Aviario</div>
            </a>

            <hr class="sidebar-divider my-0">

            <!-- Monitoreo General -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('granja.inicio') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>MONITOREO EN VIVO</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                SECTORES
            </div>

            <!-- Menú Monitoreo -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMonitoreo"
                    aria-expanded="true" aria-controls="collapseMonitoreo">
                    <i class="fas fa-th-large mr-2"></i>
                    <span>Dispositivos</span>
                </a>
                <div id="collapseMonitoreo" class="collapse" aria-labelledby="headingMonitoreo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded shadow-sm">
                        <a class="collapse-item" href="{{ route('monitoreo.alimentacion') }}"><i class="fas fa-leaf text-success mr-2"></i>Alimentación</a>
                        <a class="collapse-item" href="#"><i class="fas fa-tint text-info mr-2"></i>Hidratación</a>
                        <a class="collapse-item" href="#"><i class="fas fa-thermometer-half text-warning mr-2"></i>Temperatura</a>
                        <a class="collapse-item" href="#"><i class="fas fa-egg text-secondary mr-2"></i>Conteo Huevos</a>
                    </div>
                </div>
            </li>

            <!-- Reportes -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReportes"
                    aria-expanded="true" aria-controls="collapseReportes">
                    <i class="fas fa-chart-pie mr-2"></i>
                    <span>Reportes</span>
                </a>
                <div id="collapseReportes" class="collapse" aria-labelledby="headingReportes" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded shadow-sm">
                        <a class="collapse-item" href="#"><i class="fas fa-calendar-day text-success mr-2"></i>Reporte Diario</a>
                        <a class="collapse-item" href="#"><i class="fas fa-history text-info mr-2"></i>Historial</a>
                    </div>
                </div>
            </li>

            <!-- SECCIÓN SOLO ADMIN (AQUÍ ESTÁ EL MENÚ DE USUARIOS ENLAZADO) -->
            @if(auth()->check() && auth()->user()->role === 'admin')
            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                ADMINISTRACIÓN
            </div>

            <!-- Menú Desplegable de Usuarios -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsuarios"
                    aria-expanded="true" aria-controls="collapseUsuarios">
                    <i class="fas fa-users-cog mr-2"></i>
                    <span>Gestión Usuarios</span>
                </a>
                <div id="collapseUsuarios" class="collapse" aria-labelledby="headingUsuarios" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded shadow-sm">
                        <a class="collapse-item" href="{{ route('usuarios.index') }}"><i class="fas fa-users text-success mr-2"></i>Lista de Usuarios</a>
                        <a class="collapse-item" href="{{ route('usuarios.create') }}"><i class="fas fa-user-plus text-info mr-2"></i>Nuevo Usuario</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('granja.config') }}">
                    <i class="fas fa-fw fa-cogs"></i>
                    <span>Ajustes ESP32</span>
                </a>
            </li>
            @endif

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars text-dark"></i>
                    </button>

                    <!-- Estado del ESP32 en vivo -->
                    <div class="d-flex align-items-center bg-light px-3 py-1 rounded-pill border">
                        <span class="pulse-dot mr-2"></span>
                        <span class="small font-weight-bold text-dark mb-0">ESP32: <span class="text-success">En línea (Recepción activa)</span></span>
                    </div>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Perfil y Cierre de Sesión -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="d-flex flex-column text-right mr-2">
                                    <span class="text-gray-800 font-weight-bold small">
                                        {{ Auth::user()->name ?? 'Usuario' }}
                                    </span>
                                    <span class="badge badge-success text-uppercase" style="font-size: 0.65rem;">
                                        {{ Auth::user()->role ?? 'Propietario' }}
                                    </span>
                                </div>
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-weight: bold;">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </div>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <div class="dropdown-header text-dark font-weight-bold">Mi Cuenta</div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger font-weight-bold" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                                    Cerrar Sesión
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Contenido dinámico -->
                <div class="container-fluid">
                    @yield('contenido')
                </div>

            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white py-3">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto text-muted small">
                        <span>Sistema de Automatización y Control Avícola &copy; 2026</span>
                    </div>
                </div>
            </footer>

        </div>

    </div>

    <!-- Modal Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title font-weight-bold" id="logoutModalLabel">¿Cerrar Sesión?</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body py-4">
                    ¿Estás seguro de que deseas salir del panel de control?
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary rounded-pill px-3" type="button" data-dismiss="modal">Cancelar</button>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Salir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts Bootstrap y jQuery -->
    <script src="{{ asset('startbootstrap/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('startbootstrap/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('startbootstrap/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('startbootstrap/js/sb-admin-2.min.js') }}"></script>

    <!-- Espacio para los scripts y gráficas de cada vista -->
    @yield('scripts')

</body>

</html>