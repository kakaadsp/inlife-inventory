@extends('layouts.app')

@section('title', $item->name)

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('items.index') }}">Data Barang</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $item->name }}</span>
    </div>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="page-title">{{ $item->name }}</h1>
            <p class="page-subtitle">{{ $item->code }}</p>
        </div>
        @if(auth()->user()->canManage())
        <div class="flex gap-2">
            <a href="{{ route('items.edit', $item) }}" class="btn btn-secondary btn-sm" id="btn-edit-item">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Item Photo & Quick Info --}}
        <div class="space-y-4">
            <div class="card p-4">
                <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                     class="w-full aspect-square object-cover rounded-xl bg-slate-100 dark:bg-slate-800">

                <div class="mt-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Stok Saat Ini</span>
                        @if($item->stock === 0)
                            <span class="badge badge-red text-sm font-bold">0 (Habis)</span>
                        @elseif($item->is_low_stock)
                            <span class="badge badge-yellow text-sm font-bold">{{ $item->stock }} (Menipis)</span>
                        @else
                            <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $item->stock }}</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Stok Minimum</span>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $item->min_stock }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Kondisi</span>
                        @php $condClasses = ['good' => 'badge-green', 'fair' => 'badge-yellow', 'damaged' => 'badge-red']; @endphp
                        <span class="badge {{ $condClasses[$item->condition] ?? 'badge-gray' }}">{{ $item->condition_label }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Kategori</span>
                        <span class="badge badge-blue">{{ $item->category?->name ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Stock progress bar --}}
            @if($item->min_stock > 0)
            <div class="card p-4">
                <p class="text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Tingkat Stok</p>
                @php
                    $pct = min(100, round(($item->stock / max($item->min_stock * 2, 1)) * 100));
                    $barColor = $item->stock === 0 ? 'bg-red-500' : ($item->is_low_stock ? 'bg-amber-500' : 'bg-emerald-500');
                @endphp
                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2.5">
                    <div class="{{ $barColor }} h-2.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
                <p class="text-xs text-slate-500 mt-1.5">{{ $pct }}% dari target minimal</p>
            </div>
            @endif
        </div>

        {{-- Details --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Info Card --}}
            <div class="card p-5">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Informasi Barang</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-500 mb-0.5">Kode Barang</p>
                        <p class="text-sm font-mono font-medium text-slate-800 dark:text-slate-200">{{ $item->code }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-0.5">Lokasi</p>
                        <p class="text-sm text-slate-800 dark:text-slate-200">{{ $item->location ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-0.5">Ditambahkan Oleh</p>
                        <p class="text-sm text-slate-800 dark:text-slate-200">{{ $item->creator?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-0.5">Tanggal Input</p>
                        <p class="text-sm text-slate-800 dark:text-slate-200">{{ $item->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                @if($item->description)
                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <p class="text-xs text-slate-500 mb-1">Deskripsi</p>
                    <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $item->description }}</p>
                </div>
                @endif

                @if($item->notes)
                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <p class="text-xs text-slate-500 mb-1">Catatan Internal</p>
                    <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $item->notes }}</p>
                </div>
                @endif
            </div>

            {{-- Borrowing History --}}
            <div class="card">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Riwayat Peminjaman</h2>
                </div>

                @if($item->borrowingDetails->isEmpty())
                    <div class="empty-state py-10">
                        <p class="text-sm text-slate-400">Barang ini belum pernah dipinjam</p>
                    </div>
                @else
                    <div class="table-container border-0 rounded-none">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kode Peminjaman</th>
                                    <th>Peminjam</th>
                                    <th>Qty</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->borrowingDetails->sortByDesc('created_at')->take(10) as $detail)
                                <tr>
                                    <td>
                                        <a href="{{ route('borrowings.show', $detail->borrowing) }}"
                                           class="text-brand-600 hover:text-brand-700 font-medium text-xs">
                                            {{ $detail->borrowing->borrowing_code }}
                                        </a>
                                    </td>
                                    <td class="text-xs">{{ $detail->borrowing->borrower_name }}</td>
                                    <td class="text-xs font-medium">{{ $detail->quantity }}</td>
                                    <td class="text-xs">{{ $detail->borrowing->borrow_date->format('d M Y') }}</td>
                                    <td>
                                        @php $statusBadge = ['borrowed' => 'badge-blue', 'returned' => 'badge-green', 'overdue' => 'badge-red']; @endphp
                                        <span class="badge {{ $statusBadge[$detail->borrowing->status] ?? 'badge-gray' }} text-[10px]">
                                            {{ $detail->borrowing->status_label }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
