<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Inlife Inventory — Sistem Manajemen Inventaris">

    <title>{{ config('app.name', 'Inlife Inventory') }} — Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface-50 antialiased">

    <div class="min-h-screen flex">

        {{-- Left: Branding Panel --}}
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-brand-600 via-brand-700 to-brand-900 relative overflow-hidden flex-col items-center justify-center p-12">
            {{-- Background decorations --}}
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <div class="absolute top-10 left-10 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-accent-500 rounded-full blur-2xl"></div>
            </div>

            {{-- Grid pattern overlay --}}
            <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 30px 30px;"></div>

            <div class="relative z-10 text-center">
                {{-- Logo --}}
                <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-xl overflow-hidden p-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>

                <h1 class="text-4xl font-bold text-white mb-2">Inlife Inventory</h1>
                <p class="text-brand-200 text-lg mb-10">Inlife</p>

                <div class="space-y-4 text-left max-w-xs mx-auto">
                    <div class="flex items-center gap-3 text-white/90">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm">Manajemen inventaris terpusat</span>
                    </div>
                    <div class="flex items-center gap-3 text-white/90">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm">Sistem peminjaman barang real-time</span>
                    </div>
                    <div class="flex items-center gap-3 text-white/90">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm">Laporan & export Excel / PDF</span>
                    </div>
                    <div class="flex items-center gap-3 text-white/90">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm">Multi-role: Admin, Staff, Manager</span>
                    </div>
                </div>
            </div>

            <p class="absolute bottom-6 text-brand-300 text-xs">© {{ date('Y') }} Inlife. All rights reserved.</p>
        </div>

        {{-- Right: Login Form --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">

                {{-- Mobile Logo --}}
                <div class="flex items-center gap-3 mb-8 lg:hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain rounded-xl">
                    <div>
                        <p class="font-bold text-slate-900">Inlife Inventory</p>
                        <p class="text-xs text-slate-500">Inlife</p>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-slate-900 mb-1">Masuk ke Sistem</h2>
                <p class="text-sm text-slate-500 mb-8">Masukkan kredensial akun Anda untuk melanjutkan</p>

                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>
