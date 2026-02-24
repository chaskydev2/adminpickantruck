<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cuenta Verificada - Pick&Truck</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eeeeee;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
        }
        .content {
            padding: 20px 0;
            line-height: 1.6;
        }
        .cta-button {
            display: inline-block;
            background-color: #3490dc;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999999;
            margin-top: 20px;
            border-top: 1px solid #eeeeee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Bienvenido a Pick&Truck!</h1>
        </div>
        <div class="content">
            <p>Hola {{ $user->name }},</p>
            <p>Nos complace informarte que tus documentos han sido revisados y aprobados exitosamente. Tu cuenta en <strong>Pick&Truck</strong> ha sido verificada y se encuentra activa.</p>
            <p>A partir de este momento, puedes acceder a todas las funcionalidades de nuestra plataforma.</p>
            <p style="text-align: center;">
                <a href="{{ config('app.url') }}" class="cta-button">Ir a la Plataforma</a>
            </p>
            <p>Si tienes alguna pregunta o necesitas asistencia, no dudes en contactar a nuestro equipo de soporte.</p>
            <p>¡Gracias por confiar en nosotros!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Pick&Truck. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
