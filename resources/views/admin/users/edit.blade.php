<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">Edit Data Pengguna</h2>
                <p class="text-xs text-gray-500 mt-1">Perbarui informasi akun untuk {{ $userToEdit->name }} (Prodi Sistem Informasi)</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-gray-600 hover:text-uin bg-white border border-gray-200 px-4 py-2 rounded-xl shadow-xs transition-colors">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ selectedRole: '{{ $userToEdit->role }}' }" class="bg-white shadow-xs sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-bold text-gray-900">Formulir Pembaruan Pengguna</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Program Studi Sistem Informasi - Fakultas Sains & Teknologi</p>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.update', $userToEdit->id) }}" class="space-y-6">
                        @csrf @method('PATCH')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
                                <select name="role" x-model="selectedRole" required class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5">
                                    <option value="mahasiswa" {{ $userToEdit->role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa Sistem Informasi</option>
                                    <option value="dpl" {{ $userToEdit->role == 'dpl' ? 'selected' : '' }}>Dosen Pembimbing Lapangan (DPL)</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-1 text-xs" />
                            </div>

                            <!-- NPM / NIP -->
                            <div x-show="selectedRole === 'mahasiswa'">
                                <label for="nim" class="block text-xs font-bold text-gray-700 mb-1">NPM Mahasiswa</label>
                                <input type="text" name="nim" id="nim" value="{{ old('nim', $userToEdit->nim) }}" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5 font-mono" placeholder="Contoh: 2271xxxx">
                                <x-input-error :messages="$errors->get('nim')" class="mt-1 text-xs" />
                            </div>

                            <div x-show="selectedRole === 'dpl'">
                                <label for="nip" class="block text-xs font-bold text-gray-700 mb-1">NIP / NIDN Dosen</label>
                                <input type="text" name="nip" id="nip" value="{{ old('nip', $userToEdit->nip) }}" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5 font-mono" placeholder="Contoh: 198501012010121001">
                                <x-input-error :messages="$errors->get('nip')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $userToEdit->name) }}" required class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5">
                                <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs" />
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $userToEdit->email) }}" required class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5">
                                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Password Baru <span class="text-[11px] text-gray-400 font-normal">(Kosongkan jika tidak ingin mengubah password)</span></label>
                                <input type="password" name="password" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5" placeholder="Minimal 8 karakter">
                                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                            </div>
                        </div>
                        
                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 text-xs font-bold rounded-xl text-white bg-uin hover:bg-uin-hover focus:outline-none focus:ring-2 focus:ring-uin/30 shadow-sm transition-all">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>