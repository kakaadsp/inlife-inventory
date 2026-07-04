@extends('layouts.app')

@section('title', 'Data Barang')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 dark:text-slate-200 font-medium">Data Barang</span>
    </div>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Data Barang</h1>
            <p class="page-subtitle">{{ number_format($items->total()) }} barang terdaftar</p>
        </div>
        @if(auth()->user()->canManage())
        <a href="{{ route('items.create') }}" class="btn btn-primary" id="btn-add-item">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Barang
        </a>
        @endif
    </div>

    {{-- Filter Bar --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('items.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="search-bar flex-1">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari kode, nama, atau lokasi barang..."
                       class="form-input pl-10" id="input-search-items">
            </div>
            <select name="category_id" class="form-select w-full sm:w-44" id="select-category-filter">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="condition" class="form-select w-full sm:w-36" id="select-condition-filter">
                <option value="">Semua Kondisi</option>
                <option value="good" @selected(request('condition') === 'good')>Baik</option>
                <option value="fair" @selected(request('condition') === 'fair')>Cukup Baik</option>
                <option value="damaged" @selected(request('condition') === 'damaged')>Rusak</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm px-4 py-2.5" id="btn-filter-items">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filter
            </button>
            @if(request()->hasAny(['search', 'category_id', 'condition']))
                <a href="{{ route('items.index') }}" class="btn btn-secondary btn-sm px-4 py-2.5" id="btn-clear-filter">Reset</a>
            @endif
        </form>
    </div>

    {{-- Items Table --}}
    <div class="card">
        @if($items->isEmpty())
            <div class="empty-state py-16 px-6 flex flex-col items-center justify-center text-center animate-slide-up">
                <div class="relative mb-6">
                    <div class="absolute inset-0 rounded-full bg-brand-50 dark:bg-brand-950/20 blur-xl opacity-70 scale-150 animate-pulse-slow"></div>
                    <div class="relative w-24 h-24 rounded-2xl bg-gradient-to-tr from-brand-50 to-brand-100 dark:from-brand-900/10 dark:to-brand-800/20 flex items-center justify-center border border-brand-200/50 dark:border-brand-800/30 shadow-soft">
                        <svg class="w-12 h-12 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                    {{ request()->anyFilled(['search', 'category_id', 'condition']) ? 'Pencarian Tidak Ditemukan' : 'Inventaris Barang Kosong' }}
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mb-6 leading-relaxed">
                    {{ request()->anyFilled(['search', 'category_id', 'condition']) 
                        ? 'Tidak ada barang yang cocok dengan kriteria pencarian atau filter Anda saat ini. Silakan atur kembali filter Anda.'
                        : 'Sistem belum memiliki katalog barang inventaris. Silakan tambahkan barang inventaris baru untuk memulai pencatatan dan pengelolaan.' }}
                </p>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    @if(request()->anyFilled(['search', 'category_id', 'condition']))
                        <a href="{{ route('items.index') }}" class="btn btn-secondary shadow-sm hover:shadow-md transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89"/>
                            </svg>
                            Reset Filter Pencarian
                        </a>
                    @endif
                    @if(auth()->user()->canManage())
                        <a href="{{ route('items.create') }}" class="btn btn-primary shadow-sm hover:shadow-md transition-all duration-300" id="btn-empty-add-item">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Barang Baru
                        </a>
                    @endif
                </div>
            </div>
        @else
        <div class="table-container border-0 rounded-t-none">
            <table class="table">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Kategori</th>
                        <th class="hidden md:table-cell">Stok</th>
                        <th class="hidden lg:table-cell">Kondisi</th>
                        <th class="hidden lg:table-cell">Lokasi</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                                     class="w-10 h-10 rounded-xl object-cover flex-shrink-0 bg-slate-100">
                                <div class="min-w-0">
                                    <p class="font-medium text-slate-800 dark:text-slate-200 truncate">{{ $item->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $item->code }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-gray">{{ $item->category?->name ?? '-' }}</span>
                        </td>
                        <td class="hidden md:table-cell">
                            @if($item->stock === 0)
                                <span class="badge badge-red">Habis</span>
                            @elseif($item->is_low_stock)
                                <span class="badge badge-yellow">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/></svg>
                                    {{ $item->stock }}
                                </span>
                            @else
                                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $item->stock }}</span>
                            @endif
                        </td>
                        <td class="hidden lg:table-cell">
                            @php
                                $condClasses = ['good' => 'badge-green', 'fair' => 'badge-yellow', 'damaged' => 'badge-red'];
                            @endphp
                            <span class="badge {{ $condClasses[$item->condition] ?? 'badge-gray' }}">{{ $item->condition_label }}</span>
                        </td>
                        <td class="hidden lg:table-cell">
                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ $item->location ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('items.show', $item) }}" class="btn btn-ghost btn-sm px-2 py-1.5" title="Detail" id="btn-show-item-{{ $item->id }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @if(auth()->user()->canManage())
                                <a href="{{ route('items.edit', $item) }}" class="btn btn-ghost btn-sm px-2 py-1.5" title="Edit" id="btn-edit-item-{{ $item->id }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('items.destroy', $item) }}"
                                      onsubmit="return confirm('Yakin hapus barang {{ addslashes($item->name) }}?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm px-2 py-1.5" title="Hapus" id="btn-delete-item-{{ $item->id }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($items->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
            {{ $items->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
