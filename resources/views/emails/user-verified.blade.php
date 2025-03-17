<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Estado de Verificación de Cuenta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .container {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .logo {
            max-height: 60px;
        }
        .content {
            margin-bottom: 20px;
        }
        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 3px;
            color: white;
            display: inline-block;
        }
        .verified { background-color: #28a745; }
        .unverified { background-color: #dc3545; }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-size: 0.8em;
            color: #777;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white !important; /* Asegurarse que el texto es blanco */
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img class="logo" src="{{ asset('images/logo.png') }}" alt="Pickntruck Logo">
            <h2>Notificación de Estado de Cuenta</h2>
        </div>
        
        <div class="content">
            <p>Estimado(a) <strong>{{ $user->name }}</strong>,</p>
            
            @if($verified)
                <p>¡Buenas noticias! Tu cuenta en Pickntruck ha sido <span class="status verified">verificada</span> exitosamente.</p>
                
                <p>Esto significa que has cumplido con todos nuestros requisitos y ahora tienes acceso completo a todas las funcionalidades de la plataforma.</p>
                
                <p>Ahora puedes:</p>
                <ul>
                    <li>Publicar ofertas de carga</li>
                    <li>Participar en pujas</li>
                    <li>Acceder a todas las herramientas premium de la plataforma</li>
                </ul>
                
                <p>Agradecemos tu paciencia durante el proceso de verificación y esperamos que tu experiencia con Pickntruck sea excelente.</p>
                
                <p style="text-align: center;">
                    <a href="https://app.pickntruck.com/login" class="button">Ir a mi cuenta</a>
                </p>
            @else
                <p>Te informamos que el estado de tu cuenta en Pickntruck ha sido cambiado a <span class="status unverified">no verificada</span>.</p>
                
                <p>Esto puede deberse a una de las siguientes razones:</p>
                <ul>
                    <li>Alguno de tus documentos requeridos ha sido rechazado</li>
                    <li>Hemos detectado alguna irregularidad en tu cuenta</li>
                    <li>Tu cuenta está bajo revisión administrativa</li>
                </ul>
                
                <p>Para resolver esta situación, te sugerimos que revises el estado de tus documentos en tu perfil y te pongas en contacto con nuestro equipo de soporte si necesitas más información.</p>
                
                <p style="text-align: center;">
                    <a href="https://app.pickntruck.com/login" class="button">Revisar mi cuenta</a>
                </p>
            @endif
        </div>
        
        <div class="footer">
            <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
            <p>&copy; {{ date('Y') }} Pickntruck - Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>
