<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = Category::withCount('items')
            ->search($request->get('search'))
            ->paginate(15);

        return response()->json(['success' => true, 'data' => $categories->items(), 'meta' => ['total' => $categories->total()]]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:10', 'unique:categories,code'],
            'name' => ['required', 'string', 'max:100'],
        ]);

        $category = Category::create($validated);
        return response()->json(['success' => true, 'data' => $category], 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $category->loadCount('items')]);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:100'],
        ]);

        $category->update($validated);
        return response()->json(['success' => true, 'data' => $category]);
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->items()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Kategori masih memiliki barang.'], 422);
        }
        $category->delete();
        return response()->json(['success' => true, 'message' => 'Kategori dihapus.']);
    }
}
