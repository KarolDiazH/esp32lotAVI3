@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Gestión de Usuarios</h1>
            <p class="text-muted small mb-0">Control de accesos y roles del sistema</p>
        </div>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4">
            <i class="fas fa-user-plus mr-1"></i> Registrar Nuevo Usuario
        </a>
    </div>

    <!-- Alertas de Éxito / Error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Tabla Card -->
    <div class="card card-iot bg-white shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="border-0">Usuario</th>
                            <th class="border-0">Correo Electrónico</th>
                            <th class="border-0">Rol Asignado</th>
                            <th class="border-0">Fecha de Registro</th>
                            <th class="border-0 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $user)
                        <tr>
                            <td class="font-weight-bold text-dark">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary font-weight-bold mr-3 border" style="width: 38px; height: 38px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="text-muted">{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge badge-success px-3 py-2 text-uppercase">Administrador</span>
                                @else
                                    <span class="badge badge-info px-3 py-2 text-uppercase">Propietario</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td class="text-center">
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle" title="Eliminar Usuario">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="badge badge-light border text-muted">Sesión Activa</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection