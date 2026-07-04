@extends('layouts.app')

@section('title', 'Laporan & Export')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 dark:text-slate-200 font-medium">Laporan & Export</span>
    </div>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Laporan & Export</h1>
            <p class="page-subtitle">Unduh data inventaris dalam format Excel atau PDF</p>
        </div>
    </div>

    {{-- Export Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Export Inventaris --}}
        <div class="card p-6" id="card-export-items">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Export Data Inventaris</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Unduh daftar semua barang dalam format Excel (.xlsx)</p>
                </div>
            </div>

            <form method="GET" action="{{ route('reports.export-items') }}" id="form-export-items">
                <div class="space-y-4 mb-5">
                    <div class="form-group">
                        <label class="form-label">Filter Kategori</label>
                        <select name="category_id" class="form-select" id="select-export-category">
                            <option value="">Semua Kategori</option>
                            @php $categories = \App\Models\Category::orderBy('name')->get(); @endphp
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Filter Kondisi</label>
                        <select name="condition" class="form-select" id="select-export-condition">
                            <option value="">Semua Kondisi</option>
                            <option value="good">Baik</option>
                            <option value="fair">Cukup Baik</option>
                            <option value="damaged">Rusak</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-full" id="btn-export-items">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Unduh Excel Inventaris
                </button>
            </form>
        </div>

        {{-- Export Peminjaman Excel --}}
        <div class="card p-6" id="card-export-borrowings">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Export Data Peminjaman</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Unduh riwayat peminjaman dalam format Excel (.xlsx)</p>
                </div>
            </div>

            <form method="GET" action="{{ route('reports.export-borrowings') }}" id="form-export-borrowings">
                <div class="space-y-4 mb-5">
                    <div class="form-group">
                        <label class="form-label">Filter Status</label>
                        <select name="status" class="form-select" id="select-export-status">
                            <option value="">Semua Status</option>
                            <option value="borrowed">Dipinjam</option>
                            <option value="returned">Dikembalikan</option>
                            <option value="overdue">Terlambat</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="form-group">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="date_from" class="form-input" id="input-date-from-borrowings">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="date_to" class="form-input" id="input-date-to-borrowings">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-full" id="btn-export-borrowings">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Unduh Excel Peminjaman
                </button>
            </form>
        </div>

        {{-- Export PDF --}}
        <div class="card p-6 md:col-span-2" id="card-export-pdf">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Export Laporan PDF</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Unduh laporan peminjaman resmi dalam format PDF, siap cetak</p>
                </div>
            </div>

            <form method="GET" action="{{ route('reports.export-pdf') }}" id="form-export-pdf" target="_blank" class="flex flex-col sm:flex-row gap-4">
                <div class="form-group flex-1">
                    <label class="form-label">Filter Status</label>
                    <select name="status" class="form-select" id="select-pdf-status">
                        <option value="">Semua Status</option>
                        <option value="borrowed">Dipinjam</option>
                        <option value="returned">Dikembalikan</option>
                        <option value="overdue">Terlambat</option>
                    </select>
                </div>
                <div class="form-group flex-1">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-input" id="input-date-from-pdf">
                </div>
                <div class="form-group flex-1">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-input" id="input-date-to-pdf">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-danger w-full sm:w-auto" id="btn-export-pdf">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Unduh PDF
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const handleExport = (formId, defaultFilename) => {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const button = form.querySelector('button[type="submit"]');
            const originalContent = button.innerHTML;
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengunduh...
            `;

            try {
                const formData = new FormData(form);
                const params = new URLSearchParams();
                for (const [key, value] of formData.entries()) {
                    params.append(key, value);
                }
                const url = `${form.action}?${params.toString()}`;

                const response = await fetch(url);
                if (!response.ok) throw new Error('Download failed');

                let filename = defaultFilename;
                const disposition = response.headers.get('Content-Disposition');
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    const matches = filenameRegex.exec(disposition);
                    if (matches != null && matches[1]) { 
                        filename = matches[1].replace(/['"]/g, '');
                    }
                }

                const blob = await response.blob();
                const blobUrl = window.URL.createObjectURL(blob);
                
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = blobUrl;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                
                window.URL.revokeObjectURL(blobUrl);
                document.body.removeChild(a);
            } catch (error) {
                console.error('Export error:', error);
                alert('Gagal mengunduh laporan Excel. Silakan coba kembali.');
            } finally {
                button.disabled = false;
                button.innerHTML = originalContent;
            }
        });
    };

    handleExport('form-export-items', 'inventaris.xlsx');
    handleExport('form-export-borrowings', 'peminjaman.xlsx');
});
</script>
@endpush
