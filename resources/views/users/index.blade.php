@extends('layouts.app')

@section('title', 'Manajemen User')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 dark:text-slate-200 font-medium">Manajemen User</span>
    </div>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Manajemen User</h1>
            <p class="page-subtitle">{{ number_format($users->total()) }} pengguna terdaftar</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary" id="btn-add-user">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Tambah User
        </a>
    </div>

    {{-- Filter --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="search-bar flex-1">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau email..." class="form-input pl-10" id="input-search-users">
            </div>
            <select name="role_id" class="form-select w-full sm:w-40" id="select-role-filter">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" @selected(request('role_id') == $role->id)>{{ $role->display_name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm px-4 py-2.5">Filter</button>
            @if(request()->hasAny(['search', 'role_id']))
                <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm px-4 py-2.5">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="card">
        @if($users->isEmpty())
            <div class="empty-state py-16 px-6 flex flex-col items-center justify-center text-center animate-slide-up">
                <div class="relative mb-6">
                    <div class="absolute inset-0 rounded-full bg-brand-50 dark:bg-brand-950/20 blur-xl opacity-70 scale-150 animate-pulse-slow"></div>
                    <div class="relative w-24 h-24 rounded-2xl bg-gradient-to-tr from-brand-50 to-brand-100 dark:from-brand-900/10 dark:to-brand-800/20 flex items-center justify-center border border-brand-200/50 dark:border-brand-800/30 shadow-soft">
                        <svg class="w-12 h-12 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                    {{ request()->anyFilled(['search', 'role_id']) ? 'Pencarian Pengguna Tidak Ditemukan' : 'Tidak Ada Pengguna Terdaftar' }}
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mb-6 leading-relaxed">
                    {{ request()->anyFilled(['search', 'role_id']) 
                        ? 'Tidak ada pengguna terdaftar yang cocok dengan kriteria pencarian atau filter role Anda saat ini.'
                        : 'Belum ada pengguna lain yang terdaftar dalam sistem inventaris ini.' }}
                </p>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    @if(request()->anyFilled(['search', 'role_id']))
                        <a href="{{ route('users.index') }}" class="btn btn-secondary shadow-sm hover:shadow-md transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89"/>
                            </svg>
                            Reset Pencarian
                        </a>
                    @endif
                    <a href="{{ route('users.create') }}" class="btn btn-primary shadow-sm hover:shadow-md transition-all duration-300" id="btn-empty-add-user">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Tambah User Baru
                    </a>
                </div>
            </div>
        @else
        <div class="table-container border-0 rounded-t-none">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th class="hidden md:table-cell">Email</th>
                        <th>Role</th>
                        <th class="hidden lg:table-cell">No. HP</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="{{ !$user->is_active ? 'opacity-60' : '' }}">
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                     class="avatar w-9 h-9 flex-shrink-0">
                                <div>
                                    <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $user->name }}</p>
                                    @if($user->id === auth()->id())
                                        <span class="text-[10px] text-brand-600 font-medium">Anda</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="hidden md:table-cell">
                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ $user->email }}</span>
                        </td>
                        <td>
                            @php
                                $roleBadge = ['admin' => 'badge-red', 'staff' => 'badge-blue', 'manager' => 'badge-yellow'];
                            @endphp
                            <span class="badge {{ $roleBadge[$user->role?->name] ?? 'badge-gray' }}">
                                {{ $user->role?->display_name ?? '-' }}
                            </span>
                        </td>
                        <td class="hidden lg:table-cell">
                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ $user->phone ?? '-' }}</span>
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge badge-green">Aktif</span>
                            @else
                                <span class="badge badge-red">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-ghost btn-sm px-2 py-1.5" title="Edit" id="btn-edit-user-{{ $user->id }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $user) }}"
                                      onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ addslashes($user->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                            class="btn {{ $user->is_active ? 'btn-danger' : 'btn-secondary' }} btn-sm px-2 py-1.5"
                                            id="btn-toggle-user-{{ $user->id }}">
                                        @if($user->is_active)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        @endif
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
            {{ $users->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
