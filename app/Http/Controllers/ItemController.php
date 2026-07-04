<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Item\StoreItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;
use App\Models\Category;
use App\Models\Item;
use App\Services\CodeGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function __construct(
        private readonly CodeGeneratorService $codeGenerator
    ) {}

    public function index(Request $request): View
    {
        $items = Item::with('category')
            ->search($request->get('search'))
            ->byCategory($request->get('category_id'))
            ->byCondition($request->get('condition'))
            ->orderBy($request->get('sort', 'created_at'), $request->get('direction', 'desc'))
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('items.index', compact('items', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('items.create', compact('categories'));
    }

    public function store(StoreItemRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Auto-generate item code
        $data['code']       = $this->codeGenerator->generateItemCode($data['category_id']);
        $data['created_by'] = auth()->id();

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($data);

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Item $item): View
    {
        $item->load(['category', 'creator', 'borrowingDetails.borrowing']);
        return view('items.show', compact('item'));
    }

    public function edit(Item $item): View
    {
        $categories = Category::orderBy('name')->get();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(UpdateItemRequest $request, Item $item): RedirectResponse
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        return redirect()->route('items.show', $item)
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        // Check if item has active borrowings
        $activeBorrowings = $item->borrowingDetails()
            ->whereHas('borrowing', fn($q) => $q->whereIn('status', ['borrowed', 'overdue']))
            ->count();

        if ($activeBorrowings > 0) {
            return back()->with('error', 'Barang tidak dapat dihapus karena masih dalam status dipinjam.');
        }

        $item->delete(); // Soft delete

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
