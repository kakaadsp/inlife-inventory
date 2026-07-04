<section>
    <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-1">Informasi Profil</h2>
    <p class="text-xs text-slate-500 mb-5">Perbarui nama dan alamat email akun Anda</p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4" id="form-update-profile">
        @csrf
        @method('patch')

        <div class="form-group">
            <label class="form-label" for="name">Nama Lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                   class="form-input @error('name') border-red-400 @enderror"
                   required autofocus autocomplete="name">
            @error('name')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                   class="form-input @error('email') border-red-400 @enderror"
                   required autocomplete="username">
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert-warning mt-3">
                    <span class="text-xs">Email belum terverifikasi.
                        <button form="send-verification" class="underline font-medium">Kirim ulang verifikasi</button>
                    </span>
                </div>
                @if (session('status') === 'verification-link-sent')
                    <p class="text-xs text-emerald-600 mt-2">Link verifikasi berhasil dikirim ke email Anda.</p>
                @endif
            @endif
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit" class="btn btn-primary btn-sm" id="btn-save-profile">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                    ✓ Profil berhasil diperbarui
                </p>
            @endif
        </div>
    </form>
</section>
