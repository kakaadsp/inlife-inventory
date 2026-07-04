<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class BorrowingService
{
    public function __construct(
        private readonly CodeGeneratorService $codeGenerator
    ) {}

    /**
     * Create a new borrowing transaction with pessimistic locking.
     *
     * @throws InsufficientStockException
     */
    public function create(array $headerData, array $items, int $createdBy): Borrowing
    {
        return DB::transaction(function () use ($headerData, $items, $createdBy) {
            // Extract item IDs for locking
            $itemIds = array_column($items, 'item_id');

            // 🔒 Pessimistic lock to prevent race condition
            $lockedItems = Item::whereIn('id', $itemIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Validate stock for each item
            foreach ($items as $itemData) {
                $item = $lockedItems->get($itemData['item_id']);

                if (! $item) {
                    throw new \RuntimeException("Barang dengan ID {$itemData['item_id']} tidak ditemukan.");
                }

                if ($item->stock < $itemData['quantity']) {
                    throw new InsufficientStockException(
                        "Stok {$item->name} tidak mencukupi. Tersedia: {$item->stock}, Diminta: {$itemData['quantity']}"
                    );
                }
            }

            // Create borrowing header
            $borrowing = Borrowing::create([
                ...$headerData,
                'created_by'     => $createdBy,
                'borrowing_code' => $this->codeGenerator->generateBorrowingCode(),
                'status'         => 'borrowed',
            ]);

            // Create details and deduct stock
            foreach ($items as $itemData) {
                $item = $lockedItems->get($itemData['item_id']);

                BorrowingDetail::create([
                    'borrowing_id'     => $borrowing->id,
                    'item_id'          => $item->id,
                    'quantity'         => $itemData['quantity'],
                    'condition_before' => $item->condition,
                    'notes'            => $itemData['notes'] ?? null,
                ]);

                // Deduct stock
                $item->decrement('stock', $itemData['quantity']);
            }

            return $borrowing->load('details.item', 'creator');
        });
    }

    /**
     * Process the return of a borrowing.
     *
     * @throws \RuntimeException
     */
    public function processReturn(int $borrowingId, int $returnedBy, array $returnDetails = []): Borrowing
    {
        return DB::transaction(function () use ($borrowingId, $returnedBy, $returnDetails) {
            // Lock the borrowing row
            $borrowing = Borrowing::with('details.item')
                ->where('id', $borrowingId)
                ->whereIn('status', ['borrowed', 'overdue'])
                ->lockForUpdate()
                ->firstOrFail();

            // Update each detail's condition_after if provided
            foreach ($returnDetails as $detailId => $conditionAfter) {
                $borrowing->details()
                    ->where('id', $detailId)
                    ->update(['condition_after' => $conditionAfter]);
            }

            // Restore stock for each item
            foreach ($borrowing->details as $detail) {
                $detail->item->increment('stock', $detail->quantity);
            }

            // Update borrowing status
            $borrowing->update([
                'status'             => 'returned',
                'returned_by'        => $returnedBy,
                'actual_return_date' => now()->toDateString(),
            ]);

            return $borrowing->refresh()->load('details.item', 'returner');
        });
    }

    /**
     * Mark overdue borrowings (called by scheduled command).
     */
    public function markOverdue(): int
    {
        return Borrowing::where('status', 'borrowed')
            ->where('expected_return_date', '<', now()->toDateString())
            ->update(['status' => 'overdue']);
    }
}
