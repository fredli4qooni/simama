<section class="space-y-4">
    <header class="border-b border-rose-100 pb-4">
        <div class="flex items-center space-x-3">
            <span class="p-2 bg-rose-100 text-rose-700 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </span>
            <div>
                <h3 class="text-base font-bold text-rose-900">
                    Zona Berbahaya: Hapus Akun
                </h3>
                <p class="text-xs text-rose-600 mt-0.5">
                    Setelah akun Anda dihapus, semua data profil, pengajuan, presensi, dan logbook akan dihapus secara permanen.
                </p>
            </div>
        </div>
    </header>

    <div class="flex justify-between items-center pt-2">
        <span class="text-xs text-gray-500">Tindakan ini tidak dapat dibatalkan. Harap pastikan kembali sebelum menghapus.</span>
        <button
            type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Hapus Akun Saya
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-gray-900">
                Konfirmasi Penghapusan Akun
            </h2>

            <p class="mt-2 text-xs text-gray-600 leading-relaxed">
                Apakah Anda yakin ingin menghapus akun Anda? Masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda benar-benar ingin menghapus akun ini secara permanen.
            </p>

            <div class="mt-4">
                <label for="password" class="block text-xs font-bold text-gray-700 mb-1">Kata Sandi Anda *</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-rose-500 focus:ring-rose-500 p-2.5"
                    placeholder="Masukkan kata sandi akun..."
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-xs" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition">
                    Batal
                </button>

                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    Ya, Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>
