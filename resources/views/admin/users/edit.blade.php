<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">Edit Pengguna</h2>
                <p class="text-sm text-gray-500 mt-1">Perbarui informasi akun untuk {{ $userToEdit->name }}</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-gray-600 hover:text-uin bg-white border border-gray-300 px-4 py-2 rounded-md shadow-sm">Kembali</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.update', $userToEdit->id) }}" class="space-y-6">
                        @csrf @method('PATCH')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Peran (Role) <span class="text-red-500">*</span></label>
                                <select name="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-uin focus:ring-uin text-sm">
                                    @if(auth()->user()->role === 'admin_fakultas')
                                        <option value="admin" {{ $userToEdit->role == 'admin' ? 'selected' : '' }}>Admin Program Studi</option>
                                    @elseif(auth()->user()->role === 'admin')
                                        <option value="dpl" {{ $userToEdit->role == 'dpl' ? 'selected' : '' }}>Dosen Pembimbing Lapangan (DPL)</option>
                                        <option value="mahasiswa" {{ $userToEdit->role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                    @endif
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700">Program Studi <span class="text-red-500">*</span></label>
                                <select name="prodi_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-uin focus:ring-uin text-sm">
                                    @if(auth()->user()->role === 'admin_fakultas')
                                        @foreach($prodis as $prodi)
                                            <option value="{{ $prodi->id }}" {{ $userToEdit->prodi_id == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                                        @endforeach
                                    @else
                                        <option value="{{ auth()->user()->prodi_id }}" selected>{{ auth()->user()->prodi->nama_prodi }}</option>
                                    @endif
                                </select>
                                <x-input-error :messages="$errors->get('prodi_id')" class="mt-2" />
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $userToEdit->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-uin focus:ring-uin text-sm">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Alamat Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $userToEdit->email) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-uin focus:ring-uin text-sm">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700">Password Baru <span class="text-xs text-gray-400 font-normal">(Kosongkan jika tidak ingin mengubah password)</span></label>
                                <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-uin focus:ring-uin text-sm" placeholder="Minimal 8 karakter">
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-md text-white bg-uin hover:bg-uin-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uin shadow-sm">
                                Perbarui Data Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>