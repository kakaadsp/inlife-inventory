@extends('layouts.app')

@section('title', 'Kategori')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 dark:text-slate-200 font-medium">Kategori</span>
    </div>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Kategori Barang</h1>
            <p class="page-subtitle">{{ number_format($categories->total()) }} kategori terdaftar</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary" id="btn-add-category">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kategori
        </a>
    </div>

    {{-- Search --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('categories.index') }}" class="flex gap-3">
            <div class="search-bar flex-1">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari kategori..." class="form-input pl-10" id="input-search-categories">
            </div>
            <button type="submit" class="btn btn-primary btn-sm px-4 py-2.5">Filter</button>
            @if(request('search'))
                <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm px-4 py-2.5">Reset</a>
            @endif
        </form>
    </div>

    {{-- Categories Grid --}}
    @if($categories->isEmpty())
        <div class="card p-6">
            <div class="empty-state py-16 px-6 flex flex-col items-center justify-center text-center animate-slide-up">
                <div class="relative mb-6">
                    <div class="absolute inset-0 rounded-full bg-brand-50 dark:bg-brand-950/20 blur-xl opacity-70 scale-150 animate-pulse-slow"></div>
                    <div class="relative w-24 h-24 rounded-2xl bg-gradient-to-tr from-brand-50 to-brand-100 dark:from-brand-900/10 dark:to-brand-800/20 flex items-center justify-center border border-brand-200/50 dark:border-brand-800/30 shadow-soft">
                        <svg class="w-12 h-12 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                    {{ request('search') ? 'Pencarian Kategori Tidak Ditemukan' : 'Belum Ada Kategori' }}
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mb-6 leading-relaxed">
                    {{ request('search') 
                        ? 'Tidak ada kategori yang cocok dengan kata kunci pencarian Anda. Silakan atur kembali kata kunci pencarian Anda.'
                        : 'Sistem belum memiliki kategori barang inventaris. Mulai tambahkan kategori untuk mengorganisir barang inventaris Anda secara terstruktur.' }}
                </p>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    @if(request('search'))
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary shadow-sm hover:shadow-md transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89"/>
                            </svg>
                            Reset Pencarian
                        </a>
                    @endif
                    <a href="{{ route('categories.create') }}" class="btn btn-primary shadow-sm hover:shadow-md transition-all duration-300" id="btn-empty-add-category">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Kategori Baru
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($categories as $category)
            <div class="card p-5 hover:shadow-hover hover:-translate-y-0.5 transition-all duration-200" id="category-card-{{ $category->id }}">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-100 dark:bg-brand-900/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div class="flex gap-1" x-data="{ open: false }" class="relative">
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-ghost btn-sm px-2 py-1.5" title="Edit" id="btn-edit-cat-{{ $category->id }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        @if($category->items_count === 0)
                        <form method="POST" action="{{ route('categories.destroy', $category) }}"
                              onsubmit="return confirm('Hapus kategori {{ addslashes($category->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm px-2 py-1.5" title="Hapus" id="btn-delete-cat-{{ $category->id }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <h3 class="font-semibold text-slate-900 dark:text-white text-sm mb-0.5">{{ $category->name }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-mono mb-2">{{ $category->code }}</p>

                @if($category->description)
                    <p class="text-xs text-slate-600 dark:text-slate-400 mb-3 line-clamp-2">{{ $category->description }}</p>
                @endif

                <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <span class="text-xs text-slate-500">Jumlah Barang</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $category->items_count }}</span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($categories->hasPages())
        <div>{{ $categories->links() }}</div>
        @endif
    @endif
</div>
@endsection
