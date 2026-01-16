<div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 15px; margin-bottom: 15px;">
    <div class="flex items-center justify-between">
        <!-- Título e ícono -->
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <h2 style="font-size: 14px; font-weight: 600; color: #374151; margin: 0;">Resumen de Actividades</h2>
        </div>
        
        <!-- Contador total -->
        <span style="background: #dbeafe; color: #1e40af; font-size: 12px; font-weight: 500; padding: 2px 8px; border-radius: 9999px;">
            Total: {{ $total }}
        </span>
    </div>

    <!-- Tarjetas en línea horizontal -->
    <div class="flex space-x-3 mt-2">
        <!-- Tarjeta de Cargas -->
        <div class="flex-1 bg-blue-50 rounded p-2 text-center">
            <p style="font-size: 11px; color: #1e40af; margin: 0 0 2px 0;">Cargas</p>
            <p style="font-size: 18px; font-weight: 700; color: #1e3a8a; margin: 0;">{{ $cargas }}</p>
        </div>

        <!-- Tarjeta de Rutas -->
        <div class="flex-1 bg-green-50 rounded p-2 text-center">
            <p style="font-size: 11px; color: #166534; margin: 0 0 2px 0;">Rutas</p>
            <p style="font-size: 18px; font-weight: 700; color: #14532d; margin: 0;">{{ $rutas }}</p>
        </div>

        <!-- Tarjeta de Pujas -->
        <div class="flex-1 bg-cyan-50 rounded p-2 text-center">
            <p style="font-size: 11px; color: #6b21a8; margin: 0 0 2px 0;">Pujas</p>
            <p style="font-size: 18px; font-weight: 700; color: #581c87; margin: 0;">{{ $pujas }}</p>
        </div>
    </div>
</div>
