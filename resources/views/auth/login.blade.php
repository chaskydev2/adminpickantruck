<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Logo del login -->
    <div class="auth-logo-container mb-4 text-center">
        <img src="{{ asset('images/pickntruck.png') }}" alt="Pickntruck Logo" class="logo-image" style="height: 48px; margin: 0 auto;" />
    </div>

    <div class="card border-0 shadow-sm login-card">
        <div class="card-body py-5">
            <div class="text-center mb-4">
                <h1 class="h4 text-gray-900 mb-3">{{ __('Iniciar Sesión') }}</h1>
                <!-- Eliminado el logo duplicado con el camión -->
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group mb-4">
                    <x-input-label for="email" :value="__('Correo electrónico')" />
                    <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="form-group mb-4">
                    <div class="d-flex justify-content-between">
                        <x-input-label for="password" :value="__('Contraseña')" />
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                        @endif
                    </div>
                    <x-text-input id="password" class="form-control"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="form-group mb-3 form-check">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label class="form-check-label" for="remember_me">
                        {{ __('Recordarme') }}
                    </label>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <x-primary-button class="btn btn-primary btn-block">
                        {{ __('Iniciar Sesión') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <style>
        body {
            background-color: #1A202C !important;
            font-family: 'Montserrat', sans-serif !important;
        }
        .login-card {
            max-width: 500px;
            margin: 3rem auto;
            border-radius: 0.75rem !important;
            background-color: #ffffff !important;
        }
        .login-card h1 {
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
