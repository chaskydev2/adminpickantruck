<x-guest-layout>
    <!-- Logo del registro -->
    <div class="auth-logo-container mb-4 text-center">
        <img src="{{ asset('images/pickntruck.png') }}" alt="Pickntruck Logo" class="logo-image" style="height: 48px; margin: 0 auto;" />
    </div>

    <div class="auth-card p-4 bg-white shadow-md rounded-lg">
        <h1 class="text-center text-xl font-bold mb-4">{{ __('Registro') }}</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nombre')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="form-group mb-3">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="form-group mb-3">
                <x-input-label for="password" :value="__('Contraseña')" />

                <x-text-input id="password" class="form-control"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="form-group mb-4">
                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />

                <x-text-input id="password_confirmation" class="form-control"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="d-flex align-items-center justify-content-between mt-4">
                <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">
                    {{ __('¿Ya tienes cuenta? Inicia Sesión') }}
                </a>

                <x-primary-button class="btn btn-primary btn-block">
                    {{ __('Registrarse') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    @push('styles')
    <style>
        body {
            background-color: #1A202C !important;
            font-family: 'Montserrat', sans-serif !important;
        }
        .register-card {
            max-width: 500px;
            margin: 3rem auto;
            border-radius: 0.75rem !important;
            background-color: #ffffff !important;
        }
        .register-card h1 {
            color: #333333;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif !important;
        }
        .form-control {
            border-radius: 0.375rem;
            padding: 0.75rem 1.25rem;
            border-color: #d1d5db;
            background-color: #ffffff !important;
            color: #333333 !important;
            font-family: 'Montserrat', sans-serif !important;
        }
        .form-control:focus {
            border-color: #3b82f6;
            background-color: #ffffff !important;
        }
        label {
            font-weight: 500;
            color: #4b5563;
            font-family: 'Montserrat', sans-serif !important;
        }
        .btn-primary {
            background-color: #4299e1 !important;
            border-color: #4299e1 !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-family: 'Montserrat', sans-serif !important;
        }
        .btn-primary:hover {
            background-color: #3182ce !important;
            border-color: #3182ce !important;
        }
        a {
            color: #4299e1 !important;
            text-decoration: none !important;
            font-weight: 500;
        }
        a:hover {
            color: #3182ce !important;
            text-decoration: none !important;
        }
        .min-h-screen {
            background-color: #1A202C !important;
        }
    </style>
    @endpush
</x-guest-layout>
