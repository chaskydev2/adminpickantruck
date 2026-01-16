<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 15px; margin-bottom: 15px;">
    <div class="flex items-center justify-between">
        <!-- Título e ícono -->
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <h2 style="font-size: 14px; font-weight: 600; color: #374151; margin: 0;">Resumen de Usuarios</h2>
        </div>
        
        <!-- Contador total -->
        <span style="background: #dbeafe; color: #1e40af; font-size: 12px; font-weight: 500; padding: 2px 8px; border-radius: 9999px;">
            Total: {{ $totalusers ?? 0 }}
        </span>
    </div>

    <!-- Tarjetas en línea horizontal -->
    <div class="flex space-x-3 mt-2">
        <!-- Tarjeta de Verificados -->
        <div class="flex-1 bg-green-50 rounded p-2 text-center">
            <p style="font-size: 11px; color: #166534; margin: 0 0 2px 0;">Verificados</p>
            <p style="font-size: 18px; font-weight: 700; color: #14532d; margin: 0;">{{ $verificados ?? 0 }}</p>
        </div>

        <!-- Tarjeta de Pendientes -->
        <div class="flex-1 bg-yellow-50 rounded p-2 text-center">
            <p style="font-size: 11px; color: #854d0e; margin: 0 0 2px 0;">Pendientes</p>
            <p style="font-size: 18px; font-weight: 700; color: #713f12; margin: 0;">{{ $pendientes ?? 0 }}</p>
        </div>

        <!-- Tarjeta de Nuevos -->
        <div class="flex-1 bg-blue-50 rounded p-2 text-center">
            <p style="font-size: 11px; color: #1e40af; margin: 0 0 2px 0;">Nuevos</p>
            <p style="font-size: 18px; font-weight: 700; color: #1e3a8a; margin: 0;">{{ $nuevos ?? 0 }}</p>
        </div>
    </div>
</div>
