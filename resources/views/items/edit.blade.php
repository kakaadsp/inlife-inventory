@extends('layouts.app')

@section('title', 'Edit Barang')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('items.index') }}">Data Barang</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 dark:text-slate-200 font-medium">Edit Barang</span>
    </div>
@endsection

@section('content')
<div class="max-w-2xl">

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Barang</h1>
            <p class="page-subtitle">{{ $item->code }} — {{ $item->name }}</p>
        </div>
    </div>

    <div class="card p-6">
        <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data" id="form-edit-item">
            @csrf @method('PUT')

            <div class="space-y-5">
                {{-- Nama Barang --}}
                <div class="form-group">
                    <label class="form-label" for="name">Nama Barang <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}"
                           class="form-input @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div class="form-group">
                    <label class="form-label" for="category_id">Kategori <span class="text-red-500">*</span></label>
                    <select id="category_id" name="category_id"
                            class="form-select @error('category_id') border-red-400 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $item->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Stok & Min Stok --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label" for="stock">Stok <span class="text-red-500">*</span></label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', $item->stock) }}"
                               min="0" class="form-input @error('stock') border-red-400 @enderror">
                        @error('stock')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="min_stock">Stok Minimum <span class="text-red-500">*</span></label>
                        <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', $item->min_stock) }}"
                               min="0" class="form-input @error('min_stock') border-red-400 @enderror">
                        @error('min_stock')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Kondisi --}}
                <div class="form-group">
                    <label class="form-label" for="condition">Kondisi Barang <span class="text-red-500">*</span></label>
                    <select id="condition" name="condition"
                            class="form-select @error('condition') border-red-400 @enderror">
                        <option value="good" @selected(old('condition', $item->condition) === 'good')>Baik</option>
                        <option value="fair" @selected(old('condition', $item->condition) === 'fair')>Cukup Baik</option>
                        <option value="damaged" @selected(old('condition', $item->condition) === 'damaged')>Rusak</option>
                    </select>
                    @error('condition')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lokasi --}}
                <div class="form-group">
                    <label class="form-label" for="location">Lokasi Penyimpanan</label>
                    <input type="text" id="location" name="location" value="{{ old('location', $item->location) }}"
                           class="form-input @error('location') border-red-400 @enderror"
                           placeholder="Contoh: Gudang A - Rak 3">
                    @error('location')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="3"
                              class="form-input resize-none @error('description') border-red-400 @enderror">{{ old('description', $item->description) }}</textarea>
                    @error('description')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto --}}
                <div class="form-group">
                    <label class="form-label">Foto Barang</label>
                    <div x-data="{ preview: '{{ $item->image ? asset('storage/'.$item->image) : '' }}' }">
                        @if($item->image)
                        <div class="mb-3">
                            <img :src="preview || '{{ asset('storage/'.$item->image) }}'"
                                 alt="{{ $item->name }}"
                                 class="w-full h-40 object-cover rounded-xl border border-slate-200 dark:border-slate-700">
                        </div>
                        @endif
                        <input type="file" id="image" name="image" accept="image/*"
                               class="hidden"
                               @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : preview">
                        <label for="image"
                               class="flex items-center gap-3 px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ $item->image ? 'Ganti foto...' : 'Upload foto...' }}</span>
                        </label>
                    </div>
                    @error('image')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Notes --}}
                <div class="form-group">
                    <label class="form-label" for="notes">Catatan Internal</label>
                    <textarea id="notes" name="notes" rows="2"
                              class="form-input resize-none @error('notes') border-red-400 @enderror">{{ old('notes', $item->notes) }}</textarea>
                    @error('notes')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn btn-primary" id="btn-submit-edit-item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('items.show', $item) }}" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
