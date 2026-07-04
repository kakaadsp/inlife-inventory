@extends('layouts.app')

@section('title', 'Profil Saya')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 dark:text-slate-200 font-medium">Profil Saya</span>
    </div>
@endsection

@section('content')
<div class="max-w-2xl space-y-5">

    <div class="page-header">
        <div>
            <h1 class="page-title">Profil Saya</h1>
            <p class="page-subtitle">Kelola informasi akun dan keamanan Anda</p>
        </div>
    </div>

    {{-- Profile Info --}}
    <div class="card p-6">
        <div class="flex items-center gap-4 mb-6">
            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                 class="avatar w-16 h-16">
            <div>
                <h2 class="font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</h2>
                <p class="text-sm text-slate-500">{{ auth()->user()->email }}</p>
                <span class="badge badge-blue text-[10px] mt-1">{{ auth()->user()->role?->display_name }}</span>
            </div>
        </div>

        @include('profile.partials.update-profile-information-form')
    </div>

    {{-- Password --}}
    <div class="card p-6">
        <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Ubah Password</h2>
        @include('profile.partials.update-password-form')
    </div>

    {{-- Delete Account --}}
    <div class="card p-6 border-red-200 dark:border-red-800/50">
        <h2 class="text-sm font-semibold text-red-700 dark:text-red-400 mb-1">Hapus Akun</h2>
        <p class="text-xs text-slate-500 mb-4">Tindakan ini tidak dapat dibatalkan. Semua data akan dihapus permanen.</p>
        @include('profile.partials.delete-user-form')
    </div>

</div>
@endsection
