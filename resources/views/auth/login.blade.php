<x-guest-layout>
    
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang</h2>
        <p class="text-sm text-gray-500 mt-2">Silakan masuk untuk mengakses dasbor Anda.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" value="Email atau NIM" />
            <x-text-input id="email" class="block mt-1 w-full focus:border-uin focus:ring-uin" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Masukkan alamat email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-5" x-data="{ showPassword: false }">
            <x-input-label for="password" value="Password" />
            <div class="relative mt-1">
                <input id="password"
                       :type="showPassword ? 'text' : 'password'"
                       type="password"
                       name="password"
                       required autocomplete="current-password"
                       placeholder="••••••••"
                       class="block w-full border-gray-300 focus:border-uin focus:ring-uin rounded-md shadow-sm pr-10 text-sm text-gray-900 placeholder:text-gray-400 py-2 px-3">
                <button type="button" 
                        @click="showPassword = !showPassword"
                        tabindex="-1"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition cursor-pointer"
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
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-5">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-uin shadow-sm focus:ring-uin" name="remember">
                <span class="ms-2 text-sm text-gray-600">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-uin hover:text-uin-hover underline-offset-4 hover:underline focus:outline-none" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <div class="mt-8">
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-uin border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider hover:bg-uin-hover focus:bg-uin-hover active:bg-uin-hover focus:outline-none focus:ring-2 focus:ring-uin focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                Masuk ke Dasbor ➔
            </button>
        </div>

        @if (Route::has('register'))
            <div class="text-center pt-5 mt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500">
                    Belum memiliki akun mahasiswa?
                    <a href="{{ route('register') }}" class="font-bold text-uin hover:text-uin-hover underline underline-offset-4">
                        Daftar Sekarang
                    </a>
                </p>
            </div>
        @endif
    </form>
</x-guest-layout>