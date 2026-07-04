@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('categories.index') }}">Kategori</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 dark:text-slate-200 font-medium">Edit Kategori</span>
    </div>
@endsection

@section('content')
<div class="max-w-lg">

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Kategori</h1>
            <p class="page-subtitle">{{ $category->code }} — {{ $category->name }}</p>
        </div>
    </div>

    <div class="card p-6">
        <form method="POST" action="{{ route('categories.update', $category) }}" id="form-edit-category">
            @csrf @method('PUT')
            <div class="space-y-5">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Kategori <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}"
                               class="form-input @error('name') border-red-400 @enderror">
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="code">Kode Kategori <span class="text-red-500">*</span></label>
                        <input type="text" id="code" name="code" value="{{ old('code', $category->code) }}"
                               class="form-input uppercase @error('code') border-red-400 @enderror"
                               maxlength="10" oninput="this.value = this.value.toUpperCase()">
                        @error('code')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="3"
                              class="form-input resize-none @error('description') border-red-400 @enderror">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn btn-primary" id="btn-submit-edit-category">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
