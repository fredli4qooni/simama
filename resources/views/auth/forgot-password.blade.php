<x-guest-layout>
    <div class="mb-6 text-center sm:text-left">
        <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Lupa Kata Sandi?</h2>
        <p class="text-xs sm:text-sm text-gray-500 mt-1.5 leading-relaxed">
            Tidak masalah. Masukkan alamat email akun Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
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
                   placeholder="nama@radenintan.ac.id"
                   class="w-full text-base sm:text-sm rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin transition py-2.5 px-3.5 text-gray-900 placeholder:text-gray-400">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 sm:py-3.5 bg-uin border border-transparent rounded-xl font-bold text-xs sm:text-sm text-white uppercase tracking-wider hover:bg-uin-hover focus:bg-uin-hover active:bg-uin-hover focus:outline-none focus:ring-2 focus:ring-uin focus:ring-offset-2 transition-all duration-150 shadow-md active:scale-98">
                <span>Kirim Tautan Reset Password</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>

        <div class="text-center pt-3 border-t border-gray-100">
            <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold text-gray-600 hover:text-uin transition-colors">
                &larr; Kembali ke Halaman Masuk
            </a>
        </div>
    </form>
</x-guest-layout>
