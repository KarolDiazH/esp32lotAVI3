<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestion de Codornices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, html { height: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow: hidden; }

        /* CONTENEDOR DEL VIDEO DE FONDO */
        .video-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            overflow: hidden;
        }

        .video-container video {
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            object-fit: cover;
        }

        /* CAPA OSCURA SEMITRANSPARENTE */
        .video-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.65);
            z-index: -1;
        }

        /* CONTENEDOR CENTRAL */
        .login-wrapper {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* ICONO Y TITULO CABECERA FUERA DE LA TARJETA */
        .logo-circle {
            width: 75px;
            height: 75px;
            background-color: #2e7d32;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            margin: 0 auto 12px auto;
        }

        .header-title {
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        .header-subtitle {
            color: #d1d5db;
            font-size: 0.85rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
        }

        /* TARJETA TRANSLUCIDA (GLASSMORPHISM ESTILO CAPTURA) */
        .card-login { 
            width: 100%; 
            max-width: 410px; 
            border: 1px solid rgba(255, 255, 255, 0.2); 
            border-radius: 20px; 
            background: rgba(255, 255, 255, 0.15); 
            backdrop-filter: blur(15px); 
            -webkit-backdrop-filter: blur(15px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            color: #ffffff;
        }

        /* INPUTS REDONDEADOS Y ESTILIZADOS */
        .input-group-text-custom {
            background-color: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-end-0: none;
            border-top-left-radius: 25px;
            border-bottom-left-radius: 25px;
            color: #ffffff;
            padding-left: 15px;
        }

        .form-control-custom {
            background-color: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-start-0: none;
            border-top-right-radius: 25px;
            border-bottom-right-radius: 25px;
            color: #ffffff !important;
        }

        .form-control-custom::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-control-custom:focus {
            background-color: rgba(255, 255, 255, 0.35);
            box-shadow: none;
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* BOTON TIPO CAPSULA VERDE */
        .btn-ingresar {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            border: none;
            border-radius: 30px;
            padding: 12px;
            font-size: 1rem;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .btn-ingresar:hover {
            background: linear-gradient(135deg, #388e3c 0%, #2e7d32 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 125, 50, 0.4);
        }

        /* FOOTER DE CREDITOS */
        .footer-text {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            margin-top: 25px;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

    <div class="video-container">
<video autoplay muted loop playsinline>
 
<source src="{{ asset('startbootstrap/img/video/fondoCodorniz.mp4') }}" type="video/mp4">

</video>
    </div>

    <div class="video-overlay"></div>

    <div class="login-wrapper">

        <!-- LOGO Y TITULO CENTRAL EN LA PARTE SUPERIOR -->
        <div class="text-center mb-3">
            <div class="logo-circle">
                <i class="fas fa-egg fa-2x text-white"></i>
            </div>
            <h3 class="header-title mb-0">Granja Avicola</h3>
            <p class="header-subtitle">Sistema de Gestion de Codornices</p>
        </div>

        <!-- TARJETA DEL FORMULARIO CON EFECTO CRISTAL TRANSLUCIDO -->
        <div class="card card-login p-3">
            <div class="card-body">
                <h5 class="text-center fw-bold mb-4 text-white">Iniciar Sesion</h5>

                @if(session('error'))
                    <div class="alert alert-danger text-center py-2 shadow-sm border-0" style="background: rgba(220, 53, 69, 0.85); color: white;" role="alert">
                        <small><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</small>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label text-white-50 small ms-2">Correo Electronico</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-custom"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control form-control-custom @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="ejemplo@granja.com" required>
                        </div>
                        @error('email') <small class="text-warning ms-2">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white-50 small ms-2">Contrasenia</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-custom"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control form-control-custom @error('password') is-invalid @enderror" placeholder="••••••••" required>
                        </div>
                        @error('password') <small class="text-warning ms-2">{{ $message }}</small> @enderror
                    </div>

                    <button type="submit" class="btn btn-ingresar w-100 text-white fw-bold shadow">
                        INGRESAR <i class="fas fa-sign-in-alt ms-1"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- PIE DE PAGINA -->
        <div class="footer-text text-center">
            CODORNICES SYSTEM © 2026
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>