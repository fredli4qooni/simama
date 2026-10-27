<x-guest-layout>

    <div class="mb-6 sm:mb-8 text-center sm:text-left">
        <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Selamat Datang</h2>
        <p class="text-xs sm:text-sm text-gray-500 mt-1.5 leading-relaxed">Silakan masuk untuk mengakses sistem dan dasbor magang Anda.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                Alamat Email <span class="text-rose-500">*</span>
            </label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="nama@radenintan.ac.id atau email Anda"
                   class="w-full text-base sm:text-sm rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin transition py-2.5 px-3.5 text-gray-900 placeholder:text-gray-400">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div x-data="{ showPassword: false }">
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                Password <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <input id="password"
                       :type="showPassword ? 'text' : 'password'"
                       type="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full text-base sm:text-sm rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin transition py-2.5 pl-3.5 pr-11 text-gray-900 placeholder:text-gray-400">
                <button type="button"
                        @click="showPassword = !showPassword"
                        tabindex="-1"
                        class="absolute inset-y-0 right-0 w-11 flex items-center justify-center text-gray-400 hover:text-gray-600 focus:outline-none transition cursor-pointer"
                        :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                    <!-- Eye (show) -->
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Eye Slash (hide) -->
                    <svg x-show="showPassword" x-cloak style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Ingat Saya & Lupa Password -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-uin shadow-xs focus:ring-uin transition" name="remember">
                <span class="ms-2 text-xs sm:text-sm text-gray-600 select-none">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs sm:text-sm font-semibold text-uin hover:text-uin-hover underline-offset-4 hover:underline focus:outline-none transition-colors" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Tombol Masuk -->
        <div class="pt-2">
            <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 sm:py-3.5 bg-uin border border-transparent rounded-xl font-bold text-xs sm:text-sm text-white uppercase tracking-wider hover:bg-uin-hover focus:bg-uin-hover active:bg-uin-hover focus:outline-none focus:ring-2 focus:ring-uin focus:ring-offset-2 transition-all duration-150 shadow-md active:scale-98">
                <span>Masuk ke Dasbor</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>

        <!-- Link Registrasi -->
        @if (Route::has('register'))
            <div class="text-center pt-4 sm:pt-5 border-t border-gray-100">
                <p class="text-xs sm:text-sm text-gray-500">
                    Belum memiliki akun mahasiswa?
                    <a href="{{ route('register') }}" class="font-bold text-uin hover:text-uin-hover underline underline-offset-4 ml-1">
                        Daftar Sekarang
                    </a>
                </p>
            </div>
        @endif
    </form>
</x-guest-layout>