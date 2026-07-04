<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = Item::with('category')
            ->search($request->get('search'))
            ->byCategory($request->get('category_id'))
            ->byCondition($request->get('condition'))
            ->orderBy($request->get('sort', 'name'), $request->get('direction', 'asc'))
            ->paginate((int) $request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil diambil.',
            'data'    => $items->items(),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ],
        ]);
    }

    public function show(Item $item): JsonResponse
    {
        $item->load('category', 'creator');
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:200'],
            'stock'       => ['required', 'integer', 'min:0'],
            'min_stock'   => ['required', 'integer', 'min:0'],
            'condition'   => ['required', 'in:good,fair,damaged'],
            'location'    => ['nullable', 'string'],
        ]);

        $validated['created_by'] = auth()->id();
        $validated['code']       = app(\App\Services\CodeGeneratorService::class)->generateItemCode($validated['category_id']);

        $item = Item::create($validated);

        return response()->json(['success' => true, 'message' => 'Barang berhasil ditambahkan.', 'data' => $item], 201);
    }

    public function update(Request $request, Item $item): JsonResponse
    {
        $validated = $request->validate([
            'name'      => ['sometimes', 'string', 'max:200'],
            'stock'     => ['sometimes', 'integer', 'min:0'],
            'min_stock' => ['sometimes', 'integer', 'min:0'],
            'condition' => ['sometimes', 'in:good,fair,damaged'],
            'location'  => ['nullable', 'string'],
        ]);

        $validated['updated_by'] = auth()->id();
        $item->update($validated);

        return response()->json(['success' => true, 'message' => 'Barang berhasil diperbarui.', 'data' => $item]);
    }

    public function destroy(Item $item): JsonResponse
    {
        $item->delete();
        return response()->json(['success' => true, 'message' => 'Barang berhasil dihapus.']);
    }
}
