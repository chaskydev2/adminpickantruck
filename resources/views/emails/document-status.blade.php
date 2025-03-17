<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Actualización de Estado de Documento</title>
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
        .aprobado { background-color: #28a745; }
        .pendiente { background-color: #ffc107; color: #212529; }
        .rechazado { background-color: #dc3545; }
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
            <h2>Notificación de Estado de Documento</h2>
        </div>
        
        <div class="content">
            <p>Estimado(a) <strong>{{ $userDocument->user->name }}</strong>,</p>
            
            <p>Te escribimos para informarte que uno de tus documentos en Pickntruck ha sido revisado y su estado ha sido actualizado.</p>
            
            <p>
                <strong>Nombre del documento:</strong> {{ $userDocument->requiredDocument->name }}<br>
                <strong>Nuevo estado:</strong> 
                <span class="status {{ $status }}">{{ ucfirst($status) }}</span>
            </p>
            
            @if($status === 'rechazado')
                <p>
                    <strong>Comentarios:</strong><br>
                    {{ $userDocument->comments ?: 'No se proporcionaron comentarios.' }}
                </p>
                
                <p>Por favor, revisa los comentarios y vuelve a cargar tu documento cumpliendo con los requisitos mencionados.</p>
            @elseif($status === 'aprobado')
                <p>¡Felicidades! Tu documento cumple con nuestros requisitos y ha sido aprobado.</p>
            @else
                <p>Tu documento está en proceso de revisión. Te notificaremos cuando sea aprobado o si requiere alguna modificación.</p>
            @endif
        </div>
        
        <p>Puedes revisar el estado de todos tus documentos iniciando sesión en tu cuenta Pickntruck.</p>
        
        <p style="text-align: center;">
            <a href="https://app.pickntruck.com/login" class="button">Ir a mi cuenta</a>
        </p>
        
        <div class="footer">
            <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
            <p>&copy; {{ date('Y') }} Pickntruck - Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>
