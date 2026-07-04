@extends('layouts.app')

@section('title', $borrowing->borrowing_code)

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('borrowings.index') }}">Peminjaman</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 dark:text-slate-200 font-medium">{{ $borrowing->borrowing_code }}</span>
    </div>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h1 class="page-title">{{ $borrowing->borrowing_code }}</h1>
                @php $statusBadge = ['borrowed' => 'badge-blue', 'returned' => 'badge-green', 'overdue' => 'badge-red']; @endphp
                <span class="badge {{ $statusBadge[$borrowing->status] ?? 'badge-gray' }}">{{ $borrowing->status_label }}</span>
            </div>
            <p class="page-subtitle">Peminjaman oleh {{ $borrowing->borrower_name }}</p>
        </div>
        @if(auth()->user()->canManage() && in_array($borrowing->status, ['borrowed', 'overdue']))
        <form method="POST" action="{{ route('borrowings.return', $borrowing) }}"
              onsubmit="return confirm('Proses pengembalian semua barang dalam peminjaman ini?')">
            @csrf
            <button type="submit" class="btn btn-primary" id="btn-process-return">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"/>
                </svg>
                Proses Pengembalian
            </button>
        </form>
        @endif
    </div>

    {{-- Overdue Warning --}}
    @if($borrowing->is_overdue)
    <div class="alert-error">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <span class="text-sm font-medium">Peminjaman ini terlambat dikembalikan {{ $borrowing->overdue_days }} hari dari jadwal!</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left: Borrower Info --}}
        <div class="space-y-5">

            {{-- Borrower Card --}}
            <div class="card p-5">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Informasi Peminjam</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500">Nama</p>
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $borrowing->borrower_name }}</p>
                    </div>
                    @if($borrowing->borrower_department)
                    <div>
                        <p class="text-xs text-slate-500">Departemen</p>
                        <p class="text-sm text-slate-800 dark:text-slate-200">{{ $borrowing->borrower_department }}</p>
                    </div>
                    @endif
                    @if($borrowing->borrower_phone)
                    <div>
                        <p class="text-xs text-slate-500">No. HP</p>
                        <p class="text-sm text-slate-800 dark:text-slate-200">{{ $borrowing->borrower_phone }}</p>
                    </div>
                    @endif
                    @if($borrowing->borrower_email)
                    <div>
                        <p class="text-xs text-slate-500">Email</p>
                        <p class="text-sm text-slate-800 dark:text-slate-200">{{ $borrowing->borrower_email }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Date Info --}}
            <div class="card p-5">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Informasi Tanggal</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-slate-500">Tanggal Pinjam</p>
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $borrowing->borrow_date->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-slate-500">Est. Kembali</p>
                        <p class="text-sm font-medium {{ $borrowing->is_overdue ? 'text-red-600 dark:text-red-400' : 'text-slate-800 dark:text-slate-200' }}">
                            {{ $borrowing->expected_return_date->format('d M Y') }}
                        </p>
                    </div>
                    @if($borrowing->actual_return_date)
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-slate-500">Tgl Kembali Aktual</p>
                        <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                            {{ $borrowing->actual_return_date->format('d M Y') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- System Info --}}
            <div class="card p-5">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Info Sistem</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500">Dibuat Oleh</p>
                        <p class="text-sm text-slate-800 dark:text-slate-200">{{ $borrowing->creator?->name ?? '-' }}</p>
                    </div>
                    @if($borrowing->returner)
                    <div>
                        <p class="text-xs text-slate-500">Diproses Oleh</p>
                        <p class="text-sm text-slate-800 dark:text-slate-200">{{ $borrowing->returner->name }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-slate-500">Tanggal Buat</p>
                        <p class="text-sm text-slate-800 dark:text-slate-200">{{ $borrowing->created_at->format('d M Y H:i') }}</p>
                    </div>
                    @if($borrowing->notes)
                    <div>
                        <p class="text-xs text-slate-500">Catatan</p>
                        <p class="text-sm text-slate-800 dark:text-slate-200">{{ $borrowing->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Items --}}
        <div class="lg:col-span-2">
            <div class="card">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Daftar Barang Dipinjam</h2>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $borrowing->details->count() }} item</p>
                </div>

                <div class="table-container border-0 rounded-none">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Kategori</th>
                                <th class="text-center">Qty</th>
                                <th class="hidden md:table-cell">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowing->details as $detail)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $detail->item?->image_url }}" alt="{{ $detail->item?->name }}"
                                             class="w-9 h-9 rounded-lg object-cover bg-slate-100 flex-shrink-0">
                                        <div>
                                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $detail->item?->name ?? 'Barang Dihapus' }}</p>
                                            <p class="text-xs text-slate-500">{{ $detail->item?->code }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-gray text-[10px]">{{ $detail->item?->category?->name ?? '-' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $detail->quantity }}</span>
                                </td>
                                <td class="hidden md:table-cell">
                                    <span class="text-xs text-slate-500">{{ $detail->notes ?: '-' }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
