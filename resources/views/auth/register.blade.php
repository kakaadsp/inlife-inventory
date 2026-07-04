<x-guest-layout>
    <h2 class="text-2xl font-bold text-slate-900 mb-1">Buat Akun Baru</h2>
    <p class="text-sm text-slate-500 mb-8">Daftarkan akun untuk mengakses sistem inventaris</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-5" id="form-register">
        @csrf

        {{-- Nama --}}
        <div class="form-group">
            <label class="form-label" for="name">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   class="form-input @error('name') border-red-400 @enderror"
                   placeholder="Nama lengkap Anda"
                   required autofocus autocomplete="name">
            @error('name')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-input @error('email') border-red-400 @enderror"
                   placeholder="nama@telkomsel.co.id"
                   required autocomplete="username">
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="form-group" x-data="{ show: false }">
            <label class="form-label" for="password">Password</label>
            <div class="relative">
                <input id="password" :type="show ? 'text' : 'password'" name="password"
                       class="form-input pr-10 @error('password') border-red-400 @enderror"
                       placeholder="Minimal 8 karakter"
                       required autocomplete="new-password">
                <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="form-group">
            <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-input"
                   placeholder="Ulangi password"
                   required autocomplete="new-password">
        </div>

        {{-- Submit --}}
        <button type="submit" id="btn-register"
                class="btn btn-primary w-full py-3 text-base justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Daftar Sekarang
        </button>

        <p class="text-center text-sm text-slate-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-brand-600 hover:text-brand-700 font-medium transition-colors">
                Masuk di sini
            </a>
        </p>
    </form>
</x-guest-layout>
