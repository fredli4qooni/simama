<x-guest-layout>
    <div class="mb-6">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-uin-light text-uin text-xs font-bold mb-2.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            Registrasi Mahasiswa
        </div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Buat Akun Mahasiswa</h2>
        <p class="text-xs text-gray-500 mt-1">Daftarkan akun Anda untuk mengakses sistem pengajuan magang Fakultas Sains & Teknologi.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                Nama Lengkap Mahasiswa <span class="text-rose-500">*</span>
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                placeholder="Contoh: Ahmad Dahlan"
                class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin transition py-2.5 px-3.5 text-gray-900 placeholder:text-gray-400">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- NPM / NIM -->
        <div>
            <label for="nim" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                NPM / NIM <span class="text-gray-400 font-normal normal-case">(Nomor Pokok Mahasiswa)</span>
            </label>
            <input id="nim" type="text" name="nim" value="{{ old('nim') }}" autocomplete="off"
                placeholder="Contoh: 2271xxxx"
                class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin transition py-2.5 px-3.5 text-gray-900 placeholder:text-gray-400 font-mono">
            <x-input-error :messages="$errors->get('nim')" class="mt-1" />
        </div>

        <!-- Alamat Email -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                Alamat Email <span class="text-rose-500">*</span>
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                placeholder="nama@radenintan.ac.id atau email aktif"
                class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin transition py-2.5 px-3.5 text-gray-900 placeholder:text-gray-400">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>


        <!-- Kata Sandi -->
        <div x-data="{ showPass: false }">
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                Kata Sandi <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <input id="password" 
                    :type="showPass ? 'text' : 'password'"
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                    class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin transition py-2.5 pl-3.5 pr-10 text-gray-900 placeholder:text-gray-400">
                <button type="button" 
                    @click="showPass = !showPass"
                    tabindex="-1"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition cursor-pointer"
                    :aria-label="showPass ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                    <!-- Eye (show) -->
                    <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Eye Slash (hide) -->
                    <svg x-show="showPass" x-cloak style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Konfirmasi Kata Sandi -->
        <div x-data="{ showConfirm: false }">
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                Ulangi Kata Sandi <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <input id="password_confirmation" 
                    :type="showConfirm ? 'text' : 'password'"
                    type="password" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    placeholder="Ulangi kata sandi Anda"
                    class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin transition py-2.5 pl-3.5 pr-10 text-gray-900 placeholder:text-gray-400">
                <button type="button" 
                    @click="showConfirm = !showConfirm"
                    tabindex="-1"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition cursor-pointer"
                    :aria-label="showConfirm ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                    <!-- Eye (show) -->
                    <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Eye Slash (hide) -->
                    <svg x-show="showConfirm" x-cloak style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Tombol Submit -->
        <div class="pt-2">
            <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 bg-uin border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider hover:bg-uin-hover focus:bg-uin-hover active:bg-uin-hover focus:outline-none focus:ring-2 focus:ring-uin focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                <span>Daftar Akun Mahasiswa</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>

        <!-- Link ke Halaman Login -->
        <div class="text-center pt-2">
            <p class="text-xs text-gray-500">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-bold text-uin hover:text-uin-hover underline underline-offset-4">
                    Masuk ke Dasbor
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
