<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\Borrowing\StoreBorrowingRequest;
use App\Models\Borrowing;
use App\Models\Item;
use App\Services\BorrowingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function __construct(
        private readonly BorrowingService $borrowingService
    ) {}

    public function index(Request $request): View
    {
        $borrowings = Borrowing::with(['details.item', 'creator'])
            ->search($request->get('search'))
            ->byStatus($request->get('status'))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('borrowings.index', compact('borrowings'));
    }

    public function create(): View
    {
        $items = Item::with('category')
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('borrowings.create', compact('items'));
    }

    public function store(StoreBorrowingRequest $request): RedirectResponse
    {
        try {
            $headerData = $request->only([
                'borrower_name', 'borrower_department',
                'borrower_phone', 'borrower_email',
                'borrow_date', 'expected_return_date', 'notes',
            ]);

            $items = $request->input('items', []);

            $borrowing = $this->borrowingService->create($headerData, $items, auth()->id());

            return redirect()->route('borrowings.show', $borrowing)
                ->with('success', "Peminjaman {$borrowing->borrowing_code} berhasil dibuat.");

        } catch (InsufficientStockException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(Borrowing $borrowing): View
    {
        $borrowing->load(['details.item.category', 'creator', 'returner']);
        return view('borrowings.show', compact('borrowing'));
    }

    public function history(Request $request): View
    {
        $borrowings = Borrowing::with(['details.item', 'creator'])
            ->search($request->get('search'))
            ->byStatus($request->get('status'))
            ->when($request->get('date_from'), fn($q, $v) => $q->where('borrow_date', '>=', $v))
            ->when($request->get('date_to'), fn($q, $v) => $q->where('borrow_date', '<=', $v))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('borrowings.history', compact('borrowings'));
    }

    public function processReturn(Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->status === 'returned') {
            return back()->with('error', 'Peminjaman ini sudah dikembalikan.');
        }

        try {
            $this->borrowingService->processReturn($borrowing->id, auth()->id());

            return redirect()->route('borrowings.show', $borrowing)
                ->with('success', 'Pengembalian berhasil diproses.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
