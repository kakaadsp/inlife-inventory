<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'created_by',
        'updated_by',
        'code',
        'name',
        'description',
        'stock',
        'min_stock',
        'location',
        'condition',
        'image',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'stock'     => 'integer',
            'min_stock' => 'integer',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function borrowingDetails(): HasMany
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeSearch($query, ?string $search)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    public function scopeByCategory($query, ?int $categoryId)
    {
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        return $query;
    }

    public function scopeByCondition($query, ?string $condition)
    {
        if ($condition) {
            $query->where('condition', $condition);
        }
        return $query;
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'min_stock');
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/item-placeholder.webp');
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    public function getConditionLabelAttribute(): string
    {
        return match($this->condition) {
            'good'    => 'Baik',
            'fair'    => 'Cukup Baik',
            'damaged' => 'Rusak',
            default   => 'Unknown',
        };
    }

    public function getConditionColorAttribute(): string
    {
        return match($this->condition) {
            'good'    => 'green',
            'fair'    => 'yellow',
            'damaged' => 'red',
            default   => 'gray',
        };
    }
}
