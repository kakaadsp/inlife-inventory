<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Item;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\Color;

class ReportController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        return view('reports.index');
    }

    public function exportItems(Request $request)
    {
        $items = Item::with('category')
            ->when($request->category_id, fn($q, $v) => $q->where('category_id', $v))
            ->when($request->condition, fn($q, $v) => $q->where('condition', $v))
            ->orderBy('code')
            ->get();

        $filename = 'inventaris-' . now()->format('Ymd-His') . '.xlsx';

        return response()->streamDownload(function() use ($items) {
            $writer = new Writer();
            $writer->openToFile('php://output');

            // Header row
            $headerStyle = (new Style())->setFontBold()->setBackgroundColor('EC2028')->setFontColor(Color::WHITE);
            $writer->addRow(Row::fromValues([
                'No', 'Kode Barang', 'Nama Barang', 'Kategori',
                'Stok', 'Stok Min', 'Kondisi', 'Lokasi', 'Tanggal Input',
            ], $headerStyle));

            foreach ($items as $index => $item) {
                $writer->addRow(Row::fromValues([
                    $index + 1,
                    $item->code,
                    $item->name,
                    $item->category?->name,
                    $item->stock,
                    $item->min_stock,
                    $item->condition_label,
                    $item->location,
                    $item->created_at->format('d/m/Y'),
                ]));
            }

            $writer->close();
        }, $filename);
    }

    public function exportBorrowings(Request $request)
    {
        $borrowings = Borrowing::with(['details.item', 'creator'])
            ->when($request->status, fn($q, $v) => $q->where('status', $v))
            ->when($request->date_from, fn($q, $v) => $q->where('borrow_date', '>=', $v))
            ->when($request->date_to, fn($q, $v) => $q->where('borrow_date', '<=', $v))
            ->orderByDesc('borrow_date')
            ->get();

        $filename = 'peminjaman-' . now()->format('Ymd-His') . '.xlsx';

        return response()->streamDownload(function() use ($borrowings) {
            $writer = new Writer();
            $writer->openToFile('php://output');

            $headerStyle = (new Style())->setFontBold()->setBackgroundColor('EC2028')->setFontColor(Color::WHITE);
            $writer->addRow(Row::fromValues([
                'No', 'Kode Peminjaman', 'Nama Peminjam', 'Departemen',
                'Tgl Pinjam', 'Est. Kembali', 'Tgl Kembali Aktual',
                'Status', 'Dibuat Oleh',
            ], $headerStyle));

            foreach ($borrowings as $index => $borrowing) {
                $writer->addRow(Row::fromValues([
                    $index + 1,
                    $borrowing->borrowing_code,
                    $borrowing->borrower_name,
                    $borrowing->borrower_department,
                    $borrowing->borrow_date->format('d/m/Y'),
                    $borrowing->expected_return_date->format('d/m/Y'),
                    $borrowing->actual_return_date?->format('d/m/Y') ?? '-',
                    $borrowing->status_label,
                    $borrowing->creator?->name,
                ]));
            }

            $writer->close();
        }, $filename);
    }

    public function exportPdf(Request $request)
    {
        $borrowings = Borrowing::with(['details.item', 'creator'])
            ->when($request->status, fn($q, $v) => $q->where('status', $v))
            ->when($request->date_from, fn($q, $v) => $q->where('borrow_date', '>=', $v))
            ->when($request->date_to, fn($q, $v) => $q->where('borrow_date', '<=', $v))
            ->orderByDesc('borrow_date')
            ->get();

        $pdf = Pdf::loadView('reports.pdf-template', [
            'borrowings' => $borrowings,
            'generated_at' => now(),
            'filter' => $request->only('status', 'date_from', 'date_to'),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-peminjaman-' . now()->format('Ymd') . '.pdf');
    }
}
