<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="auth-logo-container">
        <img src="{{ asset('images/pickntruck.png') }}" alt="Pickntruck Logo" class="logo-image" style="height: 48px; margin: 0 auto;" />
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg auth-card">
        {{ $slot }}
    </div>
</div>
