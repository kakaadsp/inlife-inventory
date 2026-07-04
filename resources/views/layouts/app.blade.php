<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="appLayout()" :class="{ 'dark': isDark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="TSEL Inventory — Sistem Manajemen Inventaris PT Telkomsel">

    <title>@yield('title', 'Dashboard') — TSEL Inventory</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23EC2028'/><text x='6' y='22' font-family='Arial' font-size='18' font-weight='bold' fill='white'>T</text></svg>">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="min-h-screen bg-surface-50 dark:bg-surface-950">

    <!-- ─── Sidebar ────────────────────────────────────────────────────────── -->
    <aside class="sidebar"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-200/80 dark:border-slate-700/60">
            <div class="w-9 h-9 bg-gradient-to-br from-brand-600 to-brand-700 rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-bold text-slate-900 dark:text-white leading-tight truncate">TSEL Inventory</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight">PT Telkomsel</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="px-3 py-4 space-y-0.5 flex-1 overflow-y-auto scrollbar-hide">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5 sidebar-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Inventory section -->
            <div class="pt-4 pb-1">
                <p class="px-3 text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Inventaris</p>
            </div>

            <a href="{{ route('items.index') }}"
               class="sidebar-link {{ request()->routeIs('items.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 sidebar-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Data Barang</span>
                @php $lowStock = \App\Models\Item::whereColumn('stock','<=','min_stock')->count(); @endphp
                @if($lowStock > 0)
                    <span class="ml-auto badge badge-red text-[10px] px-1.5 py-0.5">{{ $lowStock }}</span>
                @endif
            </a>

            <a href="{{ route('categories.index') }}"
               class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 sidebar-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <span>Kategori</span>
            </a>

            <!-- Transactions -->
            <div class="pt-4 pb-1">
                <p class="px-3 text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Transaksi</p>
            </div>

            <a href="{{ route('borrowings.index') }}"
               class="sidebar-link {{ request()->routeIs('borrowings.index') || request()->routeIs('borrowings.show') || request()->routeIs('borrowings.create') ? 'active' : '' }}">
                <svg class="w-5 h-5 sidebar-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Peminjaman</span>
                @php $overdue = \App\Models\Borrowing::where('status','overdue')->count(); @endphp
                @if($overdue > 0)
                    <span class="ml-auto badge badge-red text-[10px] px-1.5 py-0.5">{{ $overdue }}</span>
                @endif
            </a>

            <a href="{{ route('borrowings.history') }}"
               class="sidebar-link {{ request()->routeIs('borrowings.history') ? 'active' : '' }}">
                <svg class="w-5 h-5 sidebar-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Riwayat</span>
            </a>

            <!-- Reports -->
            <div class="pt-4 pb-1">
                <p class="px-3 text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Laporan</p>
            </div>

            <a href="{{ route('reports.index') }}"
               class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 sidebar-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Laporan & Export</span>
            </a>

            <!-- Admin: User Management -->
            @if(auth()->user()->isAdmin())
                <div class="pt-4 pb-1">
                    <p class="px-3 text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Administrasi</p>
                </div>

                <a href="{{ route('users.index') }}"
                   class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 sidebar-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Manajemen User</span>
                </a>
            @endif
        </nav>

        <!-- User Profile at Bottom -->
        <div class="p-3 border-t border-slate-200/80 dark:border-slate-700/60">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="flex items-center gap-3 w-full p-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors group">
                    <img src="{{ auth()->user()->avatar_url }}"
                         alt="{{ auth()->user()->name }}"
                         class="avatar w-8 h-8 flex-shrink-0">
                    <div class="flex-1 min-w-0 text-left">
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200 truncate leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight">{{ auth()->user()->role?->display_name }}</p>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                    </svg>
                </button>

                <!-- Dropdown -->
                <div x-show="open" x-cloak @click.outside="open = false"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute bottom-full left-0 right-0 mb-2 bg-white dark:bg-slate-800 rounded-xl shadow-hover border border-slate-200 dark:border-slate-700 py-1.5 z-50">

                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Edit Profil
                    </a>

                    <div class="divider my-1"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item w-full text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- ─── Mobile overlay ────────────────────────────────────────────────── -->
    <div x-show="sidebarOpen"
         x-cloak
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm z-30 lg:hidden"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- ─── Main Content ────────────────────────────────────────────────── -->
    <div class="lg:pl-64 min-h-screen flex flex-col">

        <!-- Topbar -->
        <header class="sticky top-0 z-20 bg-white/80 dark:bg-surface-950/80 backdrop-blur-lg border-b border-slate-200/80 dark:border-slate-700/60">
            <div class="flex items-center gap-4 px-4 sm:px-6 h-16">

                <!-- Mobile menu button -->
                <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden p-2 -ml-1 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Breadcrumb -->
                <div class="flex-1 min-w-0 hidden sm:block">
                    @yield('breadcrumb')
                </div>

                <!-- Right actions -->
                <div class="flex items-center gap-2 ml-auto">

                    <!-- Dark mode toggle -->
                    <button @click="toggleDark()"
                            class="p-2 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-all duration-200"
                            :title="isDark ? 'Mode Terang' : 'Mode Gelap'">
                        <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="isDark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    <!-- Notifications bell -->
                    <div x-data="{ showNotif: false }" class="relative">
                        <button @click="showNotif = !showNotif" 
                                class="relative p-2 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 dark:text-slate-400 transition-all"
                                title="Notifikasi">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @php 
                                $overdueBorrowings = \App\Models\Borrowing::where('status', 'overdue')->get();
                                $lowStockItems = \App\Models\Item::whereColumn('stock', '<=', 'min_stock')->get();
                                $totalNotifCount = $overdueBorrowings->count() + $lowStockItems->count();
                            @endphp
                            @if($totalNotifCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 w-4.5 h-4.5 bg-brand-600 text-white text-[9px] font-bold rounded-full flex items-center justify-center border border-white dark:border-slate-900">{{ $totalNotifCount }}</span>
                            @endif
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="showNotif" x-cloak @click.outside="showNotif = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-hover border border-slate-200 dark:border-slate-700 py-2 z-50 overflow-hidden">
                            
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                                <span class="text-xs font-semibold text-slate-900 dark:text-white">Notifikasi</span>
                                @if($totalNotifCount > 0)
                                    <span class="badge badge-red text-[9px]">{{ $totalNotifCount }} Baru</span>
                                @endif
                            </div>

                            <div class="max-h-64 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700">
                                {{-- 1. Overdue Borrowings --}}
                                @foreach($overdueBorrowings as $bor)
                                <a href="{{ route('borrowings.show', $bor) }}" class="flex gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-950/30 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-800 dark:text-slate-200">Keterlambatan Peminjaman</p>
                                        <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ $bor->borrower_name }} ({{ $bor->borrowing_code }})</p>
                                        <p class="text-[9px] text-red-500 font-semibold mt-1">Terlambat {{ $bor->overdue_days }} hari</p>
                                    </div>
                                </a>
                                @endforeach

                                {{-- 2. Low Stock Items --}}
                                @foreach($lowStockItems as $item)
                                <a href="{{ route('items.show', $item) }}" class="flex gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-950/30 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-800 dark:text-slate-200">Stok Barang Menipis</p>
                                        <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ $item->name }}</p>
                                        <p class="text-[9px] text-amber-600 dark:text-amber-400 font-semibold mt-1">Stok saat ini: {{ $item->stock }} (Min: {{ $item->min_stock }})</p>
                                    </div>
                                </a>
                                @endforeach

                                @if($totalNotifCount === 0)
                                <div class="px-4 py-6 text-center text-slate-400 dark:text-slate-500">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-xs">Tidak ada notifikasi baru</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- User avatar (desktop) -->
                    <div class="hidden sm:flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-slate-700">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="avatar w-8 h-8">
                        <div class="hidden md:block">
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200 leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight">{{ auth()->user()->role?->display_name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 sm:p-6 animate-fade-in">

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert-success mb-5 animate-slide-up" x-data="{ show: true }" x-show="show">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm">{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto text-emerald-600 hover:text-emerald-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error mb-5 animate-slide-up" x-data="{ show: true }" x-show="show">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-sm">{{ session('error') }}</span>
                    <button @click="show = false" class="ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="py-4 px-6 border-t border-slate-200/80 dark:border-slate-700/60">
            <p class="text-center text-xs text-slate-400 dark:text-slate-500">
                © {{ date('Y') }} TSEL Inventory — PT Telkomsel. All rights reserved.
            </p>
        </footer>
    </div>

    @stack('modals')
    @stack('scripts')

    <script>
        function appLayout() {
            return {
                sidebarOpen: false,
                isDark: localStorage.getItem('theme') === 'dark' ||
                        (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
                toggleDark() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                },
                init() {
                    // Close sidebar on escape
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape') this.sidebarOpen = false;
                    });
                }
            }
        }
    </script>
</body>
</html>
