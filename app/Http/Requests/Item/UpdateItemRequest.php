<?php

declare(strict_types=1);

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->canManage();
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:2000'],
            'stock'       => ['required', 'integer', 'min:0'],
            'min_stock'   => ['required', 'integer', 'min:0'],
            'location'    => ['nullable', 'string', 'max:200'],
            'condition'   => ['required', 'in:good,fair,damaged'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,webp,jpg', 'max:2048'],
            'notes'       => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'Kategori',
            'name'        => 'Nama Barang',
            'stock'       => 'Stok',
            'min_stock'   => 'Stok Minimum',
            'condition'   => 'Kondisi',
            'image'       => 'Foto Barang',
        ];
    }
}
