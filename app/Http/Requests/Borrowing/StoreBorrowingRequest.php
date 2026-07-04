<?php

declare(strict_types=1);

namespace App\Http\Requests\Borrowing;

use Illuminate\Foundation\Http\FormRequest;

class StoreBorrowingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->canManage();
    }

    public function rules(): array
    {
        return [
            'borrower_name'        => ['required', 'string', 'max:200'],
            'borrower_department'  => ['nullable', 'string', 'max:200'],
            'borrower_phone'       => ['nullable', 'string', 'max:20'],
            'borrower_email'       => ['nullable', 'email', 'max:100'],
            'borrow_date'          => ['required', 'date'],
            'expected_return_date' => ['required', 'date', 'after_or_equal:borrow_date'],
            'notes'                => ['nullable', 'string', 'max:1000'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.item_id'      => ['required', 'exists:items,id'],
            'items.*.quantity'     => ['required', 'integer', 'min:1'],
            'items.*.notes'        => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'borrower_name'        => 'Nama Peminjam',
            'borrow_date'          => 'Tanggal Pinjam',
            'expected_return_date' => 'Estimasi Tanggal Kembali',
            'items'                => 'Barang',
            'items.*.item_id'      => 'Barang',
            'items.*.quantity'     => 'Jumlah',
        ];
    }

    public function messages(): array
    {
        return [
            'expected_return_date.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
            'items.min'                           => 'Minimal satu barang harus dipilih.',
        ];
    }
}
