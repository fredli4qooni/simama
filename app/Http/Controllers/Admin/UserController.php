<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $users = User::with('prodi')
            ->whereIn('role', ['dpl', 'mahasiswa'])
            ->latest()
            ->paginate(10);

        $prodiSI = Prodi::where('kode_prodi', 'SI')->first() ?? Prodi::first();

        return view('admin.users.index', compact('users', 'prodiSI'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:dpl,mahasiswa',
            'nim' => 'nullable|string|max:50|unique:users,nim',
            'nip' => 'nullable|string|max:50|unique:users,nip',
        ]);

        $prodiSI = Prodi::where('kode_prodi', 'SI')->first() ?? Prodi::first();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'nim' => $request->role === 'mahasiswa' ? $request->nim : null,
            'nip' => $request->role === 'dpl' ? $request->nip : null,
            'prodi_id' => $prodiSI ? $prodiSI->id : null,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Akun ' . ($request->role === 'dpl' ? 'Dosen Pembimbing' : 'Mahasiswa') . ' berhasil didaftarkan.');
    }

    public function edit(Request $request, string $id)
    {
        $userToEdit = User::findOrFail($id);
        $prodiSI = Prodi::where('kode_prodi', 'SI')->first() ?? Prodi::first();

        return view('admin.users.edit', compact('userToEdit', 'prodiSI'));
    }

    public function update(Request $request, string $id)
    {
        $userToEdit = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:dpl,mahasiswa',
            'nim' => 'nullable|string|max:50|unique:users,nim,' . $id,
            'nip' => 'nullable|string|max:50|unique:users,nip,' . $id,
            'password' => 'nullable|string|min:8',
        ]);

        $userToEdit->name = $request->name;
        $userToEdit->email = $request->email;
        $userToEdit->role = $request->role;
        $userToEdit->nim = $request->role === 'mahasiswa' ? $request->nim : null;
        $userToEdit->nip = $request->role === 'dpl' ? $request->nip : null;

        if ($request->filled('password')) {
            $userToEdit->password = Hash::make($request->password);
        }

        $userToEdit->save();

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, string $id)
    {
        $userToDelete = User::findOrFail($id);
        $currentUser = $request->user();

        if ($currentUser->id == $id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userToDelete->delete();

        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus secara permanen.');
    }
}
