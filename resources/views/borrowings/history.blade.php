@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 dark:text-slate-200 font-medium">Riwayat Peminjaman</span>
    </div>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Riwayat Peminjaman</h1>
            <p class="page-subtitle">{{ number_format($borrowings->total()) }} total transaksi</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('borrowings.history') }}" class="flex flex-col sm:flex-row gap-3 flex-wrap">
            <div class="search-bar flex-1 min-w-48">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari kode, nama, departemen..."
                       class="form-input pl-10" id="input-search-history">
            </div>
            <select name="status" class="form-select w-full sm:w-40">
                <option value="">Semua Status</option>
                <option value="borrowed" @selected(request('status') === 'borrowed')>Dipinjam</option>
                <option value="overdue" @selected(request('status') === 'overdue')>Terlambat</option>
                <option value="returned" @selected(request('status') === 'returned')>Dikembalikan</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="form-input w-full sm:w-40" placeholder="Dari tanggal" title="Dari tanggal">
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="form-input w-full sm:w-40" placeholder="Sampai tanggal" title="Sampai tanggal">
            <button type="submit" class="btn btn-primary btn-sm px-4 py-2.5">Filter</button>
            @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                <a href="{{ route('borrowings.history') }}" class="btn btn-secondary btn-sm px-4 py-2.5">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="card">
        @if($borrowings->isEmpty())
            <div class="empty-state">
                <svg class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-base font-semibold text-slate-700 dark:text-slate-300 mb-1">Tidak ada riwayat ditemukan</h3>
                <p class="text-sm text-slate-400">Coba ubah filter pencarian</p>
            </div>
        @else
        <div class="table-container border-0 rounded-t-none">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode / Peminjam</th>
                        <th class="hidden md:table-cell">Departemen</th>
                        <th>Tgl Pinjam</th>
                        <th class="hidden lg:table-cell">Est. Kembali</th>
                        <th class="hidden lg:table-cell">Tgl Kembali</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrowings as $borrowing)
                    <tr>
                        <td>
                            <a href="{{ route('borrowings.show', $borrowing) }}" class="font-mono font-medium text-brand-600 hover:text-brand-700 text-xs">
                                {{ $borrowing->borrowing_code }}
                            </a>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $borrowing->borrower_name }}</p>
                        </td>
                        <td class="hidden md:table-cell">
                            <span class="text-xs text-slate-600 dark:text-slate-400">{{ $borrowing->borrower_department ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $borrowing->borrow_date->format('d M Y') }}</span>
                        </td>
                        <td class="hidden lg:table-cell">
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $borrowing->expected_return_date->format('d M Y') }}</span>
                        </td>
                        <td class="hidden lg:table-cell">
                            <span class="text-sm {{ $borrowing->actual_return_date ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }}">
                                {{ $borrowing->actual_return_date?->format('d M Y') ?? '—' }}
                            </span>
                        </td>
                        <td>
                            @php $statusBadge = ['borrowed' => 'badge-blue', 'returned' => 'badge-green', 'overdue' => 'badge-red']; @endphp
                            <span class="badge {{ $statusBadge[$borrowing->status] ?? 'badge-gray' }}">
                                {{ $borrowing->status_label }}
                            </span>
                        </td>
                        <td>
                            <div class="flex justify-end">
                                <a href="{{ route('borrowings.show', $borrowing) }}" class="btn btn-ghost btn-sm px-2 py-1.5" id="btn-show-history-{{ $borrowing->id }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
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
