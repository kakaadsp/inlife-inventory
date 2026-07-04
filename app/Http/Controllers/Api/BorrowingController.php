<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Services\BorrowingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function __construct(private readonly BorrowingService $borrowingService) {}

    public function index(Request $request): JsonResponse
    {
        $borrowings = Borrowing::with(['details.item', 'creator'])
            ->search($request->get('search'))
            ->byStatus($request->get('status'))
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json(['success' => true, 'data' => $borrowings->items(), 'meta' => ['total' => $borrowings->total(), 'current_page' => $borrowings->currentPage()]]);
    }

    public function show(Borrowing $borrowing): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $borrowing->load(['details.item', 'creator', 'returner'])]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'borrower_name'        => ['required', 'string'],
            'borrower_department'  => ['nullable', 'string'],
            'borrow_date'          => ['required', 'date'],
            'expected_return_date' => ['required', 'date', 'after_or_equal:borrow_date'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.item_id'      => ['required', 'exists:items,id'],
            'items.*.quantity'     => ['required', 'integer', 'min:1'],
        ]);

        try {
            $borrowing = $this->borrowingService->create(
                collect($validated)->except('items')->toArray(),
                $validated['items'],
                auth()->id()
            );
            return response()->json(['success' => true, 'message' => 'Peminjaman berhasil dibuat.', 'data' => $borrowing], 201);
        } catch (InsufficientStockException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function processReturn(Borrowing $borrowing): JsonResponse
    {
        try {
            $borrowing = $this->borrowingService->processReturn($borrowing->id, auth()->id());
            return response()->json(['success' => true, 'message' => 'Pengembalian berhasil.', 'data' => $borrowing]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
