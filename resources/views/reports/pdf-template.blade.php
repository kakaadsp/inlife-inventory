<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman — TSEL Inventory</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', 'Arial', sans-serif; font-size: 10px; color: #1e293b; line-height: 1.4; }

        .header { background: #EC2028; color: white; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .header-logo { font-size: 18px; font-weight: bold; }
        .header-sub { font-size: 10px; opacity: 0.85; margin-top: 2px; }
        .header-info { text-align: right; font-size: 9px; }
        .header-info p { margin-bottom: 2px; }

        .report-title { text-align: center; margin-bottom: 16px; }
        .report-title h1 { font-size: 14px; font-weight: bold; color: #0f172a; }
        .report-title p { font-size: 9px; color: #64748b; margin-top: 3px; }

        .filter-info { background: #f8fafc; border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 4px; margin-bottom: 16px; font-size: 9px; color: #475569; }
        .filter-info strong { color: #1e293b; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        thead tr { background: #EC2028; color: white; }
        thead th { padding: 7px 8px; text-align: left; font-size: 9px; font-weight: bold; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr { border-bottom: 1px solid #e2e8f0; }
        tbody td { padding: 6px 8px; font-size: 9px; }

        .badge { display: inline-block; padding: 2px 6px; border-radius: 9999px; font-size: 8px; font-weight: bold; }
        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-red { background: #fee2e2; color: #991b1b; }

        .footer { margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 10px; display: flex; justify-content: space-between; font-size: 8px; color: #94a3b8; }
        .summary { background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px 14px; border-radius: 4px; margin-bottom: 16px; display: flex; gap: 30px; }
        .summary-item { }
        .summary-item .value { font-size: 16px; font-weight: bold; color: #0f172a; }
        .summary-item .label { font-size: 8px; color: #64748b; }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div>
            <div class="header-logo">TSEL Inventory</div>
            <div class="header-sub">PT Telkomsel — Sistem Manajemen Inventaris</div>
        </div>
        <div class="header-info">
            <p><strong>Laporan Peminjaman</strong></p>
            <p>Digenerate: {{ $generated_at->format('d M Y H:i') }} WIB</p>
        </div>
    </div>

    {{-- Title --}}
    <div class="report-title">
        <h1>Laporan Data Peminjaman</h1>
        <p>PT Telkomsel — TSEL Inventory Management System</p>
    </div>

    {{-- Filter Info --}}
    <div class="filter-info">
        <strong>Filter:</strong>
        Status: {{ isset($filter['status']) && $filter['status'] ? ucfirst($filter['status']) : 'Semua' }} &nbsp;|&nbsp;
        Periode: {{ isset($filter['date_from']) && $filter['date_from'] ? $filter['date_from'] : 'Awal' }}
        s/d {{ isset($filter['date_to']) && $filter['date_to'] ? $filter['date_to'] : 'Sekarang' }}
    </div>

    {{-- Summary --}}
    <div class="summary">
        <div class="summary-item">
            <div class="value">{{ $borrowings->count() }}</div>
            <div class="label">Total Transaksi</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ $borrowings->where('status', 'borrowed')->count() }}</div>
            <div class="label">Aktif Dipinjam</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ $borrowings->where('status', 'returned')->count() }}</div>
            <div class="label">Dikembalikan</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ $borrowings->where('status', 'overdue')->count() }}</div>
            <div class="label">Terlambat</div>
        </div>
    </div>

    {{-- Table --}}
    <table>
        <thead>
            <tr>
                <th style="width: 25px">No</th>
                <th style="width: 90px">Kode</th>
                <th>Nama Peminjam</th>
                <th style="width: 80px">Departemen</th>
                <th style="width: 60px">Tgl Pinjam</th>
                <th style="width: 60px">Est. Kembali</th>
                <th style="width: 60px">Tgl Kembali</th>
                <th style="width: 55px">Status</th>
                <th style="width: 60px">Dibuat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $i => $borrowing)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="font-family: monospace; font-size: 8px;">{{ $borrowing->borrowing_code }}</td>
                <td>{{ $borrowing->borrower_name }}</td>
                <td>{{ $borrowing->borrower_department ?? '-' }}</td>
                <td>{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                <td>{{ $borrowing->expected_return_date->format('d/m/Y') }}</td>
                <td>{{ $borrowing->actual_return_date?->format('d/m/Y') ?? '—' }}</td>
                <td>
                    @if($borrowing->status === 'borrowed')
                        <span class="badge badge-blue">Dipinjam</span>
                    @elseif($borrowing->status === 'returned')
                        <span class="badge badge-green">Kembali</span>
                    @else
                        <span class="badge badge-red">Terlambat</span>
                    @endif
                </td>
                <td>{{ $borrowing->creator?->name ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 20px; color: #94a3b8;">
                    Tidak ada data peminjaman
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        <div>© {{ date('Y') }} PT Telkomsel — TSEL Inventory. Dokumen ini digenerate secara otomatis oleh sistem.</div>
        <div>Halaman 1</div>
    </div>
</body>
</html>
