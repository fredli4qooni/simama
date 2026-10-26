<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                Manajemen Pengguna Prodi SI
            </h2>
            <p class="text-xs text-gray-500 mt-1">Kelola data akun Dosen Pembimbing Lapangan (DPL) & Mahasiswa Program Studi Sistem Informasi.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if (session('success'))
                <div class="px-4 py-3 text-sm text-uin bg-uin-light border border-uin/20 rounded-xl shadow-xs font-bold flex items-center">
                    <svg class="w-5 h-5 mr-2 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="px-4 py-3 text-sm text-rose-700 bg-rose-50 border border-rose-200 rounded-xl shadow-xs font-bold flex items-center">
                    <svg class="w-5 h-5 mr-2 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- FORM REGISTRASI PENGGUNA BARU PRODI SI -->
            <div x-data="{ selectedRole: 'mahasiswa' }" class="bg-white shadow-xs sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-uin-light/50 to-white flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Tambah Akun Baru (DPL / Mahasiswa SI)</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Daftarkan akun resmi Dosen Pembimbing atau Mahasiswa Sistem Informasi.</p>
                    </div>
                    <span class="px-3 py-1 bg-uin text-white text-[11px] font-bold rounded-full uppercase tracking-wider">Prodi Sistem Informasi</span>
                </div>

                <div class="p-6">
                    <form method="post" action="{{ route('admin.users.store') }}" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <div>
                                <label for="role" class="block text-xs font-bold text-gray-700 mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
                                <select name="role" id="role" x-model="selectedRole" required class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5">
                                    <option value="mahasiswa">Mahasiswa Sistem Informasi</option>
                                    <option value="dpl">Dosen Pembimbing Lapangan (DPL)</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-1 text-xs" />
                            </div>

                            <!-- NPM (JIKA MAHASISWA) -->
                            <div x-show="selectedRole === 'mahasiswa'">
                                <label for="nim" class="block text-xs font-bold text-gray-700 mb-1">NPM Mahasiswa <span class="text-rose-500">*</span></label>
                                <input type="text" name="nim" id="nim" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5 font-mono" placeholder="Contoh: 2271xxxx" />
                                <x-input-error :messages="$errors->get('nim')" class="mt-1 text-xs" />
                            </div>

                            <!-- NIP (JIKA DPL) -->
                            <div x-show="selectedRole === 'dpl'">
                                <label for="nip" class="block text-xs font-bold text-gray-700 mb-1">NIP / NIDN Dosen <span class="text-rose-500">*</span></label>
                                <input type="text" name="nip" id="nip" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5 font-mono" placeholder="Contoh: 198501012010121001" />
                                <x-input-error :messages="$errors->get('nip')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <label for="name" class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" id="name" required class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5" placeholder="Masukkan nama lengkap">
                                <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs" />
                            </div>
                            
                            <div>
                                <label for="email" class="block text-xs font-bold text-gray-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                                <input type="email" name="email" id="email" required class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5" placeholder="email@radenintan.ac.id">
                                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                            </div>
                            
                            <div>
                                <label for="password" class="block text-xs font-bold text-gray-700 mb-1">Password Awal <span class="text-rose-500">*</span></label>
                                <input type="password" name="password" id="password" required class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5" placeholder="Minimal 8 karakter">
                                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                            </div>
                            
                        </div>
                        
                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 text-xs font-bold rounded-xl text-white bg-uin hover:bg-uin-hover focus:outline-none focus:ring-2 focus:ring-uin/30 transition-all shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Daftarkan Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABEL DAFTAR PENGGUNA PRODI SI -->
            <div class="bg-white shadow-xs sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Daftar Pengguna Aktif</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Menampilkan seluruh Dosen Pembimbing (DPL) dan Mahasiswa di Program Studi Sistem Informasi.</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left text-gray-600">
                        <thead class="text-[11px] text-gray-700 uppercase bg-gray-50 border-b border-gray-200 tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-bold">Nama & Identitas</th>
                                <th class="px-6 py-4 font-bold">Alamat Email</th>
                                <th class="px-6 py-4 font-bold">Peran (Role)</th>
                                <th class="px-6 py-4 font-bold text-center">Status</th>
                                <th class="px-6 py-4 font-bold text-center w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($users as $u)
                            <tr class="bg-white hover:bg-blue-50/30 transition-colors duration-150">
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex items-center space-x-3">
                                        @if($u->avatar_url)
                                            <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}" class="w-9 h-9 rounded-full object-cover shadow-xs ring-1 ring-gray-200 flex-shrink-0">
                                        @else
                                            <div class="w-9 h-9 rounded-full bg-uin-light text-uin flex items-center justify-center font-bold text-xs flex-shrink-0 border border-uin/20">
                                                {{ substr($u->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">{{ $u->name }}</div>
                                            <div class="text-[11px] text-gray-500 font-mono mt-0.5">
                                                @if($u->nim) NPM: {{ $u->nim }}
                                                @elseif($u->nip) NIP: {{ $u->nip }}
                                                @else -
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle font-mono text-gray-700">
                                    {{ $u->email }}
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    @if($u->role === 'dpl')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">Dosen Pembimbing</span>
                                    @elseif($u->role === 'mahasiswa')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-uin-light text-uin">Mahasiswa SI</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-800">{{ strtoupper($u->role) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-middle text-center">
                                    @if($u->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-middle text-center">
                                    @if(auth()->user()->id !== $u->id)
                                        <div class="flex justify-center items-center space-x-3">
                                            <a href="{{ route('admin.users.edit', $u->id) }}" class="text-[11px] uppercase tracking-wider font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                                Edit
                                            </a>
    
                                            <span class="text-gray-300">|</span>
    
                                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="inline-block m-0 p-0">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }} secara permanen?')" class="text-[11px] uppercase tracking-wider font-bold text-rose-600 hover:text-rose-800 transition-colors">
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
                                        Belum ada data DPL atau Mahasiswa terdaftar di sistem.
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