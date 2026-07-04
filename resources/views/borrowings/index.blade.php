@extends('layouts.app')

@section('title', 'Peminjaman')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 dark:text-slate-200 font-medium">Peminjaman</span>
    </div>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Peminjaman Aktif</h1>
            <p class="page-subtitle">{{ number_format($borrowings->total()) }} data peminjaman</p>
        </div>
        @if(auth()->user()->canManage())
        <a href="{{ route('borrowings.create') }}" class="btn btn-primary" id="btn-add-borrowing">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Peminjaman
        </a>
        @endif
    </div>

    {{-- Filter --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('borrowings.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="search-bar flex-1">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari kode, nama peminjam, atau departemen..."
                       class="form-input pl-10" id="input-search-borrowings">
            </div>
            <select name="status" class="form-select w-full sm:w-40" id="select-status-filter">
                <option value="">Semua Status</option>
                <option value="borrowed" @selected(request('status') === 'borrowed')>Dipinjam</option>
                <option value="overdue" @selected(request('status') === 'overdue')>Terlambat</option>
                <option value="returned" @selected(request('status') === 'returned')>Dikembalikan</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm px-4 py-2.5">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('borrowings.index') }}" class="btn btn-secondary btn-sm px-4 py-2.5">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="card">
        @if($borrowings->isEmpty())
            <div class="empty-state py-16 px-6 flex flex-col items-center justify-center text-center animate-slide-up">
                <div class="relative mb-6">
                    <div class="absolute inset-0 rounded-full bg-brand-50 dark:bg-brand-950/20 blur-xl opacity-70 scale-150 animate-pulse-slow"></div>
                    <div class="relative w-24 h-24 rounded-2xl bg-gradient-to-tr from-brand-50 to-brand-100 dark:from-brand-900/10 dark:to-brand-800/20 flex items-center justify-center border border-brand-200/50 dark:border-brand-800/30 shadow-soft">
                        <svg class="w-12 h-12 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                    {{ request()->anyFilled(['search', 'status']) ? 'Pencarian Peminjaman Tidak Ditemukan' : 'Tidak Ada Data Peminjaman' }}
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mb-6 leading-relaxed">
                    {{ request()->anyFilled(['search', 'status']) 
                        ? 'Tidak ada data peminjaman yang cocok dengan kriteria pencarian atau status yang dipilih. Silakan atur filter pencarian.'
                        : 'Belum ada transaksi peminjaman barang inventaris. Silakan buat peminjaman baru untuk mulai mendistribusikan barang.' }}
                </p>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('borrowings.index') }}" class="btn btn-secondary shadow-sm hover:shadow-md transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89"/>
                            </svg>
                            Reset Filter Pencarian
                        </a>
                    @endif
                    @if(auth()->user()->canManage())
                        <a href="{{ route('borrowings.create') }}" class="btn btn-primary shadow-sm hover:shadow-md transition-all duration-300" id="btn-empty-add-borrowing">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Buat Peminjaman Baru
                        </a>
                    @endif
                </div>
            </div>
        @else
        <div class="table-container border-0 rounded-t-none">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode / Peminjam</th>
                        <th class="hidden md:table-cell">Departemen</th>
                        <th>Tgl Pinjam</th>
                        <th>Est. Kembali</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrowings as $borrowing)
                    <tr class="{{ $borrowing->status === 'overdue' ? 'bg-red-50/30 dark:bg-red-900/10' : '' }}">
                        <td>
                            <a href="{{ route('borrowings.show', $borrowing) }}" class="font-medium text-brand-600 hover:text-brand-700 text-xs">
                                {{ $borrowing->borrowing_code }}
                            </a>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $borrowing->borrower_name }}</p>
                            <p class="text-xs text-slate-500">{{ $borrowing->details->count() }} item</p>
                        </td>
                        <td class="hidden md:table-cell">
                            <span class="text-xs text-slate-600 dark:text-slate-400">{{ $borrowing->borrower_department ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $borrowing->borrow_date->format('d M Y') }}</span>
                        </td>
                        <td>
                            <span class="text-sm {{ $borrowing->is_overdue ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-700 dark:text-slate-300' }}">
                                {{ $borrowing->expected_return_date->format('d M Y') }}
                            </span>
                            @if($borrowing->is_overdue)
                                <p class="text-xs text-red-500 mt-0.5">Terlambat {{ $borrowing->overdue_days }} hari</p>
                            @endif
                        </td>
                        <td>
                            @php $statusBadge = ['borrowed' => 'badge-blue', 'returned' => 'badge-green', 'overdue' => 'badge-red']; @endphp
                            <span class="badge {{ $statusBadge[$borrowing->status] ?? 'badge-gray' }}">
                                {{ $borrowing->status_label }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('borrowings.show', $borrowing) }}" class="btn btn-ghost btn-sm px-2 py-1.5" title="Detail" id="btn-show-borrowing-{{ $borrowing->id }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @if(auth()->user()->canManage() && in_array($borrowing->status, ['borrowed', 'overdue']))
                                <form method="POST" action="{{ route('borrowings.return', $borrowing) }}"
                                      onsubmit="return confirm('Proses pengembalian peminjaman {{ addslashes($borrowing->borrowing_code) }}?')">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm px-2 py-1.5 text-[11px]" id="btn-return-{{ $borrowing->id }}" title="Proses Pengembalian">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"/>
                                        </svg>
                                        Kembalikan
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

        @if($borrowings->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
            {{ $borrowings->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
