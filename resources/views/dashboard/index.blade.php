@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <div class="breadcrumb">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span class="text-slate-800 dark:text-slate-200 font-medium">Dashboard</span>
    </div>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Selamat datang, {{ auth()->user()->name }}! Ini ringkasan sistem hari ini.</p>
        </div>
        <div class="text-right hidden sm:block">
            <p class="text-xs text-slate-400">{{ now()->translatedFormat('l, d F Y') }}</p>
            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ now()->format('H:i') }} WIB</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Barang --}}
        <div class="stat-card">
            <div class="stat-icon bg-blue-100 dark:bg-blue-900/30">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['total_items']) }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total Barang</p>
            </div>
        </div>

        {{-- Barang Tersedia --}}
        <div class="stat-card">
            <div class="stat-icon bg-emerald-100 dark:bg-emerald-900/30">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['available_items']) }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Barang Tersedia</p>
            </div>
        </div>

        {{-- Dipinjam Aktif --}}
        <div class="stat-card">
            <div class="stat-icon bg-amber-100 dark:bg-amber-900/30">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['active_borrowings']) }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Dipinjam Aktif</p>
            </div>
        </div>

        {{-- Terlambat / Stok Menipis --}}
        @if($stats['overdue_borrowings'] > 0)
        <div class="stat-card border border-red-200 dark:border-red-800">
            <div class="stat-icon bg-red-100 dark:bg-red-900/30">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($stats['overdue_borrowings']) }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Terlambat Kembali</p>
            </div>
        </div>
        @else
        <div class="stat-card">
            <div class="stat-icon bg-violet-100 dark:bg-violet-900/30">
                <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['returned_this_month']) }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kembali Bulan Ini</p>
            </div>
        </div>
        @endif
    </div>

    {{-- Charts + Low Stock --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Monthly Chart (spans 2 cols) --}}
        <div class="card p-5 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Tren Peminjaman</h2>
                    <p class="text-xs text-slate-500 mt-0.5">12 bulan terakhir</p>
                </div>
                <span class="badge badge-blue text-[10px]">Bulanan</span>
            </div>
            <canvas id="monthlyChart" height="120"></canvas>
        </div>

        {{-- Category Donut --}}
        <div class="card p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Distribusi Kategori</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Berdasarkan jumlah barang</p>
                </div>
            </div>
            @if(count($categoryChart) > 0)
                <canvas id="categoryChart" height="180"></canvas>
            @else
                <div class="flex flex-col items-center justify-center h-40 text-center">
                    <svg class="w-10 h-10 text-slate-300 dark:text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <p class="text-xs text-slate-400">Belum ada kategori</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Borrowings + Low Stock --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Active Borrowings --}}
        <div class="card lg:col-span-2">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200/80 dark:border-slate-700/60">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Peminjaman Aktif Terbaru</h2>
                <a href="{{ route('borrowings.index') }}" class="text-xs text-brand-600 hover:text-brand-700 font-medium transition-colors">Lihat Semua →</a>
            </div>

            @if($recentBorrowings->isEmpty())
                <div class="empty-state">
                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm text-slate-400">Tidak ada peminjaman aktif</p>
                </div>
            @else
                <div class="table-container rounded-none border-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kode / Peminjam</th>
                                <th>Barang</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBorrowings as $borrowing)
                            <tr>
                                <td>
                                    <a href="{{ route('borrowings.show', $borrowing) }}" class="font-medium text-brand-600 hover:text-brand-700 text-xs">
                                        {{ $borrowing->borrowing_code }}
                                    </a>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $borrowing->borrower_name }}</p>
                                </td>
                                <td>
                                    <p class="text-xs text-slate-600 dark:text-slate-400">
                                        {{ $borrowing->details->count() }} item
                                    </p>
                                </td>
                                <td>
                                    <p class="text-xs {{ $borrowing->is_overdue ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-600 dark:text-slate-400' }}">
                                        {{ $borrowing->expected_return_date->format('d M Y') }}
                                    </p>
                                    @if($borrowing->is_overdue)
                                        <p class="text-[10px] text-red-500">+{{ $borrowing->overdue_days }} hari</p>
                                    @endif
                                </td>
                                <td>
                                    @if($borrowing->status === 'overdue')
                                        <span class="badge badge-red text-[10px]">Terlambat</span>
                                    @else
                                        <span class="badge badge-blue text-[10px]">Dipinjam</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Low Stock Alert --}}
        <div class="card">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200/80 dark:border-slate-700/60">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Stok Menipis</h2>
                @if($lowStockItems->isNotEmpty())
                    <span class="badge badge-red text-[10px]">{{ $lowStockItems->count() }} item</span>
                @endif
            </div>

            @if($lowStockItems->isEmpty())
                <div class="empty-state py-10">
                    <svg class="w-10 h-10 text-emerald-300 dark:text-emerald-700 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-slate-400">Semua stok mencukupi</p>
                </div>
            @else
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($lowStockItems as $item)
                    <div class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-slate-800 dark:text-slate-200 truncate">{{ $item->name }}</p>
                            <p class="text-[10px] text-slate-500 mt-0.5">{{ $item->category?->name }}</p>
                        </div>
                        <div class="ml-3 flex-shrink-0 text-right">
                            <p class="text-sm font-bold {{ $item->stock === 0 ? 'text-red-600' : 'text-amber-600' }}">{{ $item->stock }}</p>
                            <p class="text-[10px] text-slate-400">/ {{ $item->min_stock }} min</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="px-5 py-3 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('items.index') }}" class="text-xs text-brand-600 hover:text-brand-700 font-medium">Kelola Barang →</a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(148,163,184,0.1)' : 'rgba(148,163,184,0.2)';
    const textColor = isDark ? '#94a3b8' : '#64748b';

    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: @json($monthlyChart['labels']),
                datasets: [{
                    label: 'Peminjaman',
                    data: @json($monthlyChart['data']),
                    backgroundColor: 'rgba(236, 32, 40, 0.15)',
                    borderColor: '#EC2028',
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y} peminjaman`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor, font: { size: 10 }, maxRotation: 45 }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: textColor, font: { size: 10 }, precision: 0 }
                    }
                }
            }
        });
    }

    // Category Donut
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        const catData = @json($categoryChart);
        const colors = [
            '#EC2028', '#3b82f6', '#10b981', '#f59e0b',
            '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16'
        ];
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: catData.map(d => d.name),
                datasets: [{
                    data: catData.map(d => d.total),
                    backgroundColor: colors.slice(0, catData.length),
                    borderWidth: 0,
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            font: { size: 10 },
                            padding: 12,
                            boxWidth: 10,
                            usePointStyle: true,
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
