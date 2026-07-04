@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('items.index') }}">Data Barang</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 dark:text-slate-200 font-medium">Tambah Barang</span>
    </div>
@endsection

@section('content')
<div class="max-w-2xl">

    <div class="page-header">
        <div>
            <h1 class="page-title">Tambah Barang</h1>
            <p class="page-subtitle">Isi form di bawah untuk mendaftarkan barang baru</p>
        </div>
    </div>

    <div class="card p-6">
        <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" id="form-create-item">
            @csrf

            <div class="space-y-5">
                {{-- Nama Barang --}}
                <div class="form-group">
                    <label class="form-label" for="name">Nama Barang <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="form-input @error('name') border-red-400 @enderror"
                           placeholder="Contoh: Laptop Dell Inspiron 15">
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
                            <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Stok & Min Stok --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label" for="stock">Stok Awal <span class="text-red-500">*</span></label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}"
                               min="0" class="form-input @error('stock') border-red-400 @enderror">
                        @error('stock')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="min_stock">Stok Minimum <span class="text-red-500">*</span></label>
                        <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', 1) }}"
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
                        <option value="good" @selected(old('condition') === 'good')>Baik</option>
                        <option value="fair" @selected(old('condition') === 'fair')>Cukup Baik</option>
                        <option value="damaged" @selected(old('condition') === 'damaged')>Rusak</option>
                    </select>
                    @error('condition')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lokasi --}}
                <div class="form-group">
                    <label class="form-label" for="location">Lokasi Penyimpanan</label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}"
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
                              class="form-input resize-none @error('description') border-red-400 @enderror"
                              placeholder="Deskripsi singkat tentang barang ini...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto --}}
                <div class="form-group">
                    <label class="form-label" for="image">Foto Barang</label>
                    <div class="relative" x-data="{ preview: null }">
                        <input type="file" id="image" name="image" accept="image/*"
                               class="hidden"
                               @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                        <label for="image"
                               class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl cursor-pointer hover:border-brand-400 transition-colors bg-slate-50 dark:bg-slate-800/50">
                            <template x-if="!preview">
                                <div class="text-center">
                                    <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-slate-500">Klik untuk upload foto</p>
                                    <p class="text-xs text-slate-400 mt-0.5">PNG, JPG, WEBP maks. 2MB</p>
                                </div>
                            </template>
                            <template x-if="preview">
                                <img :src="preview" class="h-full w-full object-contain p-2 rounded-xl">
                            </template>
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
                              class="form-input resize-none @error('notes') border-red-400 @enderror"
                              placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn btn-primary" id="btn-submit-create-item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Barang
                    </button>
                    <a href="{{ route('items.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
