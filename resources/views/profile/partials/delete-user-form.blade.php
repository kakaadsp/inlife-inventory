<section x-data="{ showModal: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">

    <button type="button"
            @click="showModal = true"
            class="btn btn-danger btn-sm" id="btn-open-delete-modal">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Hapus Akun Saya
    </button>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">

        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showModal = false"></div>

        <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-hover max-w-md w-full p-6 z-10"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Hapus Akun?</h3>
                    <p class="text-xs text-slate-500">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>

            <p class="text-sm text-slate-600 dark:text-slate-400 mb-5">
                Setelah akun dihapus, semua data akan hilang secara permanen. Masukkan password Anda untuk konfirmasi.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}" id="form-delete-account">
                @csrf
                @method('delete')

                <div class="form-group mb-4">
                    <label class="form-label" for="delete_password">Password</label>
                    <input id="delete_password" name="password" type="password"
                           class="form-input @if($errors->userDeletion->get('password')) border-red-400 @endif"
                           placeholder="Masukkan password Anda">
                    @if($errors->userDeletion->get('password'))
                        <p class="form-error">{{ $errors->userDeletion->first('password') }}</p>
                    @endif
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="showModal = false" class="btn btn-secondary btn-sm">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger btn-sm" id="btn-confirm-delete">
                        Ya, Hapus Akun Saya
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
