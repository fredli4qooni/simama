<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                Manajemen Pengguna
            </h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data akun pengguna, pendaftaran baru, dan status akses sistem.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if (session('success'))
                <div class="px-4 py-3 text-sm text-uin bg-uin-light border border-uin/20 rounded-lg shadow-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="px-4 py-3 text-sm text-red-700 bg-red-100 border border-red-200 rounded-lg shadow-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-lg font-bold text-gray-900">Registrasi Akun Baru</h2>
                    <p class="text-sm text-gray-500">
                        @if(auth()->user()->role === 'admin_fakultas')
                            Tambahkan akun Admin Program Studi baru.
                        @else
                            Tambahkan akun DPL atau Mahasiswa untuk prodi Anda.
                        @endif
                    </p>
                </div>

                <div class="p-6">
                    <form method="post" action="{{ route('admin.users.store') }}" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label for="role" class="block text-sm font-bold text-gray-700">Peran (Role) <span class="text-red-500">*</span></label>
                                <select name="role" id="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-uin focus:ring-uin text-sm">
                                    <option value="">-- Pilih Peran --</option>
                                    @if(auth()->user()->role === 'admin_fakultas')
                                        <option value="admin">Admin Program Studi</option>
                                    @elseif(auth()->user()->role === 'admin')
                                        <option value="dpl">Dosen Pembimbing Lapangan (DPL)</option>
                                        <option value="mahasiswa">Mahasiswa</option>
                                    @endif
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>

                            <div>
                                <label for="prodi_id" class="block text-sm font-bold text-gray-700">Program Studi <span class="text-red-500">*</span></label>
                                <select name="prodi_id" id="prodi_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-uin focus:ring-uin text-sm">
                                    @if(auth()->user()->role === 'admin_fakultas')
                                        <option value="">-- Pilih Prodi --</option>
                                        @foreach($prodis as $prodi)
                                            <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                        @endforeach
                                    @else
                                        <option value="{{ auth()->user()->prodi_id }}" selected>{{ auth()->user()->prodi->nama_prodi }}</option>
                                    @endif
                                </select>
                                <x-input-error :messages="$errors->get('prodi_id')" class="mt-2" />
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-bold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-uin focus:ring-uin text-sm" placeholder="Masukkan nama lengkap">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-bold text-gray-700">Alamat Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-uin focus:ring-uin text-sm" placeholder="email@radenintan.ac.id">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-bold text-gray-700">Password <span class="text-red-500">*</span></label>
                                <input type="password" name="password" id="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-uin focus:ring-uin text-sm" placeholder="Minimal 8 karakter">
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            
                        </div>
                        
                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-md text-white bg-uin hover:bg-uin-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uin transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Simpan Akun Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Daftar Pengguna Terdaftar</h2>
                        <p class="text-sm text-gray-500">
                            @if(auth()->user()->role === 'admin_fakultas')
                                Menampilkan seluruh pengguna dari berbagai program studi.
                            @else
                                Menampilkan seluruh DPL dan Mahasiswa di program studi Anda.
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-bold">Nama & Email</th>
                                <th class="px-6 py-4 font-bold">Peran (Role)</th>
                                <th class="px-6 py-4 font-bold">Program Studi</th>
                                <th class="px-6 py-4 font-bold text-center">Status</th>
                                <th class="px-6 py-4 font-bold text-center w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($users as $user)
                            <tr class="bg-white hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 align-middle">
                                    <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-100 text-blue-800">Admin Prodi</span>
                                    @elseif($user->role === 'dpl')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-purple-100 text-purple-800">DPL</span>
                                    @elseif($user->role === 'mahasiswa')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-800">Mahasiswa</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-red-100 text-red-800">{{ strtoupper($user->role) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-middle font-medium text-gray-800">
                                    {{ $user->prodi ? $user->prodi->nama_prodi : 'Lintas Prodi' }}
                                </td>
                                <td class="px-6 py-4 align-middle text-center">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-uin-light text-uin border border-uin/20">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-middle text-center">
                                    @if(auth()->user()->id !== $user->id)
                                        <div class="flex justify-center items-center space-x-3">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-[11px] uppercase tracking-wider font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                                Edit
                                            </a>
    
                                            <span class="text-gray-300">|</span>
    
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block m-0 p-0">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }} secara permanen?')" class="text-[11px] uppercase tracking-wider font-bold text-red-600 hover:text-red-800 transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Akun Anda</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        Belum ada data pengguna terdaftar di lingkup wewenang Anda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    @if($users->hasPages())
                        <div class="p-4 border-t border-gray-100">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>