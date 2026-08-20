@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Registrar Nuevo Usuario</h1>
            <p class="text-muted small mb-0">Crea una cuenta para un Administrador o un Propietario</p>
        </div>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary rounded-pill shadow-sm px-4">
            <i class="fas fa-arrow-left mr-1"></i> Volver a la lista
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card card-iot bg-white shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">

                    <form action="{{ route('usuarios.store') }}" method="POST">
                        @csrf

                        <!-- Nombre Completo -->
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark small">Nombre Completo</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-user text-muted"></i></span>
                                </div>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ej: Juan Pérez" required>
                            </div>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark small">Correo Electrónico</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-envelope text-muted"></i></span>
                                </div>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="ejemplo@granja.com" required>
                            </div>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Rol del Sistema -->
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark small">Rol en el Sistema</label>
                            <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Selecciona el Rol --</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador (Control Técnico & Hardware ESP32)</option>
                                <option value="propietario" {{ old('role') == 'propietario' ? 'selected' : '' }}>Propietario (Reportes, Producción & Stock)</option>
                            </select>
                            @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="row">
                            <!-- Contraseña -->
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold text-dark small">Contraseña</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-lock text-muted"></i></span>
                                    </div>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mínimo 6 caracteres" required>
                                </div>
                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div class="col-md-6 form-group mb-4">
                                <label class="font-weight-bold text-dark small">Confirmar Contraseña</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-check-double text-muted"></i></span>
                                    </div>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repite la contraseña" required>
                                </div>
                            </div>
                        </div>

                        <!-- Botón Guardar -->
                        <button type="submit" class="btn btn-primary btn-block rounded-pill py-2 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> Guardar Usuario
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection