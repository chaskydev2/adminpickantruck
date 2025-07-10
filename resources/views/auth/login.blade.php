<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Pick&Truck</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
        }
        
        body {
            background-color: #212529;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
            color: white;
        }
        
        .logo-container img {
            max-width: 360px; /* Duplicado de 180px */
            height: auto;
            margin-bottom: 1rem; /* Aumentado para mejor espaciado */
            width: 100%; /* Asegura que la imagen sea responsive */
        }
        
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
        }
        
        .login-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 1.8rem;
            text-align: center;
            font-size: 1.5rem;
        }
        
        .form-control {
            height: 45px;
            border: 1px solid #e1e5ee;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .btn-login {
            background-color: var(--primary-color);
            border: none;
            border-radius: 6px;
            padding: 0.7rem;
            font-weight: 500;
            font-size: 1rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background-color: #0b5ed7;
            transform: translateY(-1px);
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .forgot-password {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s ease;
        }
        
        .forgot-password:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }
        
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.2rem 0;
        }
        
        .form-check-label {
            font-size: 0.85rem;
            color: #6c757d;
            user-select: none;
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="{{ asset('images/pickntruck.png') }}" alt="Pick&Truck Logo">
    </div>
    
    <div class="login-container">
        <h1 class="login-title">Iniciar Sesión</h1>
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="Ingresa tu correo electrónico" 
                           autocomplete="email" 
                           required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="Ingresa tu contraseña" 
                           autocomplete="current-password" 
                           required>
                </div>
            </div>
            
            <div class="form-footer">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Recordarme
                    </label>
                </div>
                <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
            </div>
            
            <button type="submit" class="btn btn-primary btn-login w-100">
                Iniciar Sesión
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
