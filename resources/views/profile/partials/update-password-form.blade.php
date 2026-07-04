<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-4" id="form-update-password">
        @csrf
        @method('put')

        <div class="form-group">
            <label class="form-label" for="update_password_current_password">Password Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password"
                   class="form-input @if($errors->updatePassword->get('current_password')) border-red-400 @endif"
                   autocomplete="current-password" placeholder="••••••••">
            @if($errors->updatePassword->get('current_password'))
                <p class="form-error">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div class="form-group">
            <label class="form-label" for="update_password_password">Password Baru</label>
            <input id="update_password_password" name="password" type="password"
                   class="form-input @if($errors->updatePassword->get('password')) border-red-400 @endif"
                   autocomplete="new-password" placeholder="Minimal 8 karakter">
            @if($errors->updatePassword->get('password'))
                <p class="form-error">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div class="form-group">
            <label class="form-label" for="update_password_password_confirmation">Konfirmasi Password Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                   class="form-input" autocomplete="new-password" placeholder="Ulangi password baru">
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit" class="btn btn-primary btn-sm" id="btn-save-password">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Ubah Password
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                    ✓ Password berhasil diperbarui
                </p>
            @endif
        </div>
    </form>
</section>
