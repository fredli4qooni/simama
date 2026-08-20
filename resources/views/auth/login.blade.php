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

        <div class="mt-5">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="block mt-1 w-full focus:border-uin focus:ring-uin"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />
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
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-uin border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-uin-hover focus:bg-uin-hover active:bg-uin-hover focus:outline-none focus:ring-2 focus:ring-uin focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                Masuk ke Dasbor
            </button>
        </div>
    </form>
</x-guest-layout>