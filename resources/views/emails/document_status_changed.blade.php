@php
    $user = $document->user ?? null;
    $status = $document->status ?? 'actualizado';
    $comment = $document->comments ?? null;
    $mainAppUrl = env('MAIN_APP_URL', 'http://localhost:8000');
    $documentUrl = $document->document_url ?? ($mainAppUrl . '/documents/' . ($document->user_id ?? '') . '/' . ($document->file_path ?? ''));
@endphp

<div style="font-family: Arial, Helvetica, sans-serif; color: #222; line-height: 1.5;">
    <h2 style="color: #0d6efd;">Estado de tu documento: {{ ucfirst($status) }}</h2>

    <p>Hola {{ $user->name ?? 'Usuario' }},</p>

    @if($status === 'aprobado')
        <p>Tu documento ha sido verificado y aprobado por nuestro equipo. Ya puedes continuar con el uso normal de la plataforma.</p>
    @elseif($status === 'rechazado')
        <p>Lamentablemente tu documento ha sido <strong>rechazado</strong>. A continuación encontrarás el motivo (si fue proporcionado):</p>
        @if($comment)
            <blockquote style="background:#f8f9fa;border-left:4px solid #ddd;padding:8px 12px;margin:8px 0;">{{ $comment }}</blockquote>
        @else
            <p><em>No se proporcionó un motivo específico.</em></p>
        @endif
    @else
        <p>El estado de tu documento ha cambiado a: <strong>{{ $status }}</strong>.</p>
    @endif

    <p>Puedes ver o descargar el documento desde aquí:</p>
    <p><a href="{{ $documentUrl }}" target="_blank">Ver/Descargar documento</a></p>

    <p>Si tienes dudas, responde a este correo o consulta tu panel de usuario.</p>

    <p>Saludos,<br>Equipo PickNTruck</p>
</div>
