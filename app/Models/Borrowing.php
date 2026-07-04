<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'returned_by',
        'borrowing_code',
        'borrower_name',
        'borrower_department',
        'borrower_phone',
        'borrower_email',
        'borrow_date',
        'expected_return_date',
        'actual_return_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date'          => 'date',
            'expected_return_date' => 'date',
            'actual_return_date'   => 'date',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function returner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeSearch($query, ?string $search)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('borrower_name', 'like', "%{$search}%")
                  ->orWhere('borrowing_code', 'like', "%{$search}%")
                  ->orWhere('borrower_department', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    public function scopeByStatus($query, ?string $status)
    {
        if ($status) {
            $query->where('status', $status);
        }
        return $query;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'borrowed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'borrowed')
                     ->where('expected_return_date', '<', now()->toDateString());
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'borrowed'  => 'Dipinjam',
            'returned'  => 'Dikembalikan',
            'overdue'   => 'Terlambat',
            default     => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'borrowed'  => 'blue',
            'returned'  => 'green',
            'overdue'   => 'red',
            default     => 'gray',
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'borrowed'
            && $this->expected_return_date->isPast();
    }

    public function getOverdueDaysAttribute(): int
    {
        if (! $this->is_overdue) {
            return 0;
        }
        return (int) $this->expected_return_date->diffInDays(now());
    }
}
