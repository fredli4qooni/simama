<section>
    <header class="border-b border-gray-100 pb-4 mb-6">
        <div class="flex items-center space-x-3">
            <span class="p-2 bg-blue-100 text-blue-700 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </span>
            <div>
                <h3 class="text-base font-bold text-gray-900">
                    Perbarui Kata Sandi
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    Gunakan kata sandi yang aman dan tidak mudah ditebak untuk melindungi akun Anda.
                </p>
            </div>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-gray-700 mb-1">Kata Sandi Saat Ini *</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5" autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 text-xs" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold text-gray-700 mb-1">Kata Sandi Baru *</label>
            <input id="update_password_password" name="password" type="password" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5" autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 text-xs" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold text-gray-700 mb-1">Ulangi Kata Sandi Baru *</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5" autocomplete="new-password" placeholder="Ulangi kata sandi baru" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 text-xs" />
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            @if (session('status') === 'password-updated')
            <span
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 3000)"
                class="text-xs font-bold text-emerald-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Kata sandi berhasil diubah!
            </span>
            @else
            <span></span>
            @endif

            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gray-900 hover:bg-black text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Ubah Kata Sandi
            </button>
        </div>
    </form>
</section>
