<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use App\Models\Item;

class CodeGeneratorService
{
    /**
     * Generate a unique item code in format: INV-{CAT_CODE}-{SEQUENCE}
     * Example: INV-ELK-0001
     */
    public function generateItemCode(int|string $categoryId): string
    {
        $categoryId = (int) $categoryId;
        $category = Category::findOrFail($categoryId);

        // Get the highest sequence number for this category
        $lastItem = Item::withTrashed()
            ->where('category_id', $categoryId)
            ->where('code', 'like', "INV-{$category->code}-%")
            ->orderByDesc('id')
            ->first();

        $sequence = 1;
        if ($lastItem) {
            $parts    = explode('-', $lastItem->code);
            $sequence = (int) end($parts) + 1;
        }

        return sprintf('INV-%s-%04d', $category->code, $sequence);
    }

    /**
     * Generate a unique borrowing code in format: BRW-{YYYYMMDD}-{SEQUENCE}
     * Example: BRW-20240115-0001
     */
    public function generateBorrowingCode(): string
    {
        $today  = now()->format('Ymd');
        $prefix = "BRW-{$today}";

        // Find the last borrowing today
        $lastBorrowing = \App\Models\Borrowing::where('borrowing_code', 'like', "{$prefix}-%")
            ->orderByDesc('id')
            ->first();

        $sequence = 1;
        if ($lastBorrowing) {
            $parts    = explode('-', $lastBorrowing->borrowing_code);
            $sequence = (int) end($parts) + 1;
        }

        return sprintf('%s-%04d', $prefix, $sequence);
    }
}
