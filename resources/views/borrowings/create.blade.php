@extends('layouts.app')

@section('title', 'Buat Peminjaman')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('borrowings.index') }}">Peminjaman</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 dark:text-slate-200 font-medium">Buat Peminjaman</span>
    </div>
@endsection

@section('content')
<div x-data="borrowingForm()" class="max-w-3xl space-y-5">

    <div class="page-header">
        <div>
            <h1 class="page-title">Buat Peminjaman Baru</h1>
            <p class="page-subtitle">Isi data peminjam dan pilih barang yang akan dipinjam</p>
        </div>
    </div>

    <form method="POST" action="{{ route('borrowings.store') }}" id="form-create-borrowing" @submit.prevent="submitForm">
        @csrf

        <div class="space-y-5">

            {{-- Informasi Peminjam --}}
            <div class="card p-6">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Informasi Peminjam</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div class="form-group sm:col-span-2">
                        <label class="form-label" for="borrower_name">Nama Peminjam <span class="text-red-500">*</span></label>
                        <input type="text" id="borrower_name" name="borrower_name" value="{{ old('borrower_name') }}"
                               class="form-input @error('borrower_name') border-red-400 @enderror"
                               placeholder="Nama lengkap peminjam">
                        @error('borrower_name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="borrower_department">Departemen</label>
                        <input type="text" id="borrower_department" name="borrower_department" value="{{ old('borrower_department') }}"
                               class="form-input @error('borrower_department') border-red-400 @enderror"
                               placeholder="Contoh: IT Division">
                        @error('borrower_department') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="borrower_phone">Nomor HP</label>
                        <input type="text" id="borrower_phone" name="borrower_phone" value="{{ old('borrower_phone') }}"
                               class="form-input @error('borrower_phone') border-red-400 @enderror"
                               placeholder="08xxxxxxxxxx">
                        @error('borrower_phone') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="borrower_email">Email</label>
                        <input type="email" id="borrower_email" name="borrower_email" value="{{ old('borrower_email') }}"
                               class="form-input @error('borrower_email') border-red-400 @enderror"
                               placeholder="nama@telkomsel.co.id">
                        @error('borrower_email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="borrow_date">Tanggal Pinjam <span class="text-red-500">*</span></label>
                        <input type="date" id="borrow_date" name="borrow_date" value="{{ old('borrow_date', date('Y-m-d')) }}"
                               class="form-input @error('borrow_date') border-red-400 @enderror">
                        @error('borrow_date') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="expected_return_date">Estimasi Tanggal Kembali <span class="text-red-500">*</span></label>
                        <input type="date" id="expected_return_date" name="expected_return_date" value="{{ old('expected_return_date') }}"
                               class="form-input @error('expected_return_date') border-red-400 @enderror">
                        @error('expected_return_date') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group sm:col-span-2">
                        <label class="form-label" for="notes">Catatan</label>
                        <textarea id="notes" name="notes" rows="2"
                                  class="form-input resize-none"
                                  placeholder="Catatan atau keterangan tambahan...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Pilih Barang --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Daftar Barang</h2>
                    <button type="button" @click="addItem()" class="btn btn-secondary btn-sm" id="btn-add-item-row">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Barang
                    </button>
                </div>

                @error('items')
                    <div class="alert-error mb-4">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span class="text-sm">{{ $message }}</span>
                    </div>
                @enderror

                <div class="space-y-3">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-start gap-3 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                            <div class="flex-1">
                                <label class="form-label text-[11px]">Barang</label>
                                <select :name="`items[${index}][item_id]`" x-model="item.item_id"
                                        class="form-select text-sm"
                                        :id="`select-item-${index}`">
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($items as $availItem)
                                    <option value="{{ $availItem->id }}" data-stock="{{ $availItem->stock }}">
                                        {{ $availItem->name }} (Stok: {{ $availItem->stock }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-28">
                                <label class="form-label text-[11px]">Jumlah</label>
                                <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity"
                                       min="1" class="form-input text-sm"
                                       :id="`input-qty-${index}`">
                            </div>
                            <div class="w-28">
                                <label class="form-label text-[11px]">Catatan</label>
                                <input type="text" :name="`items[${index}][notes]`" x-model="item.notes"
                                       class="form-input text-sm" placeholder="Opsional"
                                       :id="`input-notes-${index}`">
                            </div>
                            <button type="button" @click="removeItem(index)"
                                    class="btn btn-danger btn-sm px-2 py-1.5 mt-5 flex-shrink-0" title="Hapus baris"
                                    :id="`btn-remove-item-${index}`">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>

                    <div x-show="items.length === 0" class="text-center py-8 text-slate-400 text-sm">
                        Belum ada barang ditambahkan. Klik "Tambah Barang" untuk memulai.
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary" id="btn-submit-create-borrowing">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Peminjaman
                </button>
                <a href="{{ route('borrowings.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function borrowingForm() {
    return {
        items: [{ item_id: '', quantity: 1, notes: '' }],

        addItem() {
            this.items.push({ item_id: '', quantity: 1, notes: '' });
        },

        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            } else {
                alert('Harus ada minimal 1 barang dalam peminjaman.');
            }
        },

        submitForm(e) {
            if (this.items.some(i => !i.item_id)) {
                alert('Pastikan semua barang dipilih.');
                return;
            }
            e.target.submit();
        }
    }
}
</script>
@endpush
