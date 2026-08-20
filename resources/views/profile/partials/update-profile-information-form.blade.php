<section x-data="{
    photoPreview: null,
    previewPhoto(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.photoPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}">
    <header class="border-b border-gray-100 pb-4 mb-6">
        <div class="flex items-center space-x-3">
            <span class="p-2 bg-uin-light text-uin rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </span>
            <div>
                <h3 class="text-base font-bold text-gray-900">
                    Informasi Data Akun & Foto Profil
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    Unggah foto formal mahasiswa, perbarui nama, email, serta capaian akademik.
                </p>
            </div>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('patch')

        <!-- FOTO PROFIL UPLOAD & PREVIEW -->
        <div class="p-4 bg-gray-50/80 rounded-2xl border border-gray-200 space-y-3">
            <label class="block text-xs font-bold text-gray-700">Foto Profil Mahasiswa</label>
            
            <div class="flex items-center space-x-4">
                <!-- Preview Image -->
                <div class="relative w-16 h-16 rounded-xl overflow-hidden shadow-sm border-2 border-white ring-2 ring-gray-200 flex-shrink-0 bg-white">
                    <template x-if="photoPreview">
                        <img :src="photoPreview" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!photoPreview">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-uin-light text-uin flex items-center justify-center font-black text-xl">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </template>
                </div>

                <div class="flex-1">
                    <input type="file" name="avatar" id="avatar" accept="image/png,image/jpeg,image/jpg,image/webp" @change="previewPhoto" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-xl cursor-pointer bg-white file:mr-3 file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-bold file:bg-uin-light file:text-uin hover:file:bg-uin/20">
                    <p class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG, atau WebP (Maks. 2MB). Disarankan foto formal/rapi.</p>
                    <x-input-error class="mt-1 text-xs" :messages="$errors->get('avatar')" />
                </div>
            </div>
        </div>

        <div>
            <label for="name" class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap *</label>
            <input id="name" name="name" type="text" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5" value="{{ old('name', $user->name) }}" required autocomplete="name" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-gray-700 mb-1">Alamat Email *</label>
            <input id="email" name="email" type="email" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2 p-3 bg-amber-50 rounded-xl border border-amber-200">
                <p class="text-xs text-amber-800">
                    Email Anda belum terverifikasi.
                    <button form="send-verification" class="underline font-bold text-amber-900 hover:text-amber-950">
                        Klik di sini untuk mengirim ulang email verifikasi.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-bold text-xs text-emerald-600">
                    Link verifikasi baru telah dikirimkan ke alamat email Anda.
                </p>
                @endif
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
            <div>
                <label for="total_sks" class="block text-xs font-bold text-gray-700 mb-1">Total SKS Ditempuh *</label>
                <input id="total_sks" name="total_sks" type="number" min="0" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5" value="{{ old('total_sks', $user->total_sks) }}" required />
                <p class="text-[11px] text-gray-400 mt-0.5">Syarat magang min. 100 SKS</p>
                <x-input-error class="mt-1 text-xs" :messages="$errors->get('total_sks')" />
            </div>

            <div>
                <label for="ipk" class="block text-xs font-bold text-gray-700 mb-1">IPK Terakhir *</label>
                <input id="ipk" name="ipk" type="number" step="0.01" min="0" max="4.00" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5" value="{{ old('ipk', $user->ipk) }}" required />
                <p class="text-[11px] text-gray-400 mt-0.5">Skala 0.00 - 4.00</p>
                <x-input-error class="mt-1 text-xs" :messages="$errors->get('ipk')" />
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            @if (session('status') === 'profile-updated')
            <span
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 3000)"
                class="text-xs font-bold text-emerald-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Perubahan profil berhasil disimpan!
            </span>
            @else
            <span></span>
            @endif

            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-uin hover:bg-uin-hover text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Profil & Foto
            </button>
        </div>
    </form>
</section>