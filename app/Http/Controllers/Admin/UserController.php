<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $user = $request->user();

        $users = User::with('prodi')
            ->when($user->role === 'admin', function ($q) use ($user) {
                return $q->where('prodi_id', $user->prodi_id);
            })
            ->latest()
            ->paginate(10);

        $prodis = Prodi::all();

        return view('admin.users.index', compact('users', 'prodis'));
    }

    public function store(Request $request)
    {
        $currentUser = $request->user();

        $allowedRoles = ($currentUser->role === 'admin_fakultas')
            ? ['admin']
            : ['dpl', 'mahasiswa'];

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:' . implode(',', $allowedRoles),
            'prodi_id' => 'required|exists:prodis,id',
        ]);

        if ($currentUser->role === 'admin' && $currentUser->prodi_id != $request->prodi_id) {
            return redirect()->back()->with('error', 'Otoritas ditolak. Anda hanya bisa mengelola prodi Anda sendiri.');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'prodi_id' => $request->prodi_id,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Akun berhasil didaftarkan ke sistem.');
    }

    public function edit(\Illuminate\Http\Request $request, string $id)
    {
        $userToEdit = User::findOrFail($id);
        $currentUser = $request->user();

        if ($currentUser->role === 'admin' && $currentUser->prodi_id !== $userToEdit->prodi_id) {
            return redirect()->back()->with('error', 'Akses ditolak. Pengguna ini berada di luar prodi Anda.');
        }

        $prodis = Prodi::all();
        return view('admin.users.edit', compact('userToEdit', 'prodis'));
    }

    public function update(\Illuminate\Http\Request $request, string $id)
    {
        $userToEdit = User::findOrFail($id);
        $currentUser = $request->user();

        if ($currentUser->role === 'admin' && $currentUser->prodi_id !== $userToEdit->prodi_id) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $allowedRoles = ($currentUser->role === 'admin_fakultas') ? ['admin'] : ['dpl', 'mahasiswa'];

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:' . implode(',', $allowedRoles),
            'prodi_id' => 'required|exists:prodis,id',
            'password' => 'nullable|string|min:8',
        ]);

        $userToEdit->name = $request->name;
        $userToEdit->email = $request->email;
        $userToEdit->role = $request->role;
        $userToEdit->prodi_id = $request->prodi_id;

        if ($request->filled('password')) {
            $userToEdit->password = Hash::make($request->password);
        }

        $userToEdit->save();

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(\Illuminate\Http\Request $request, string $id)
    {
        $userToDelete = User::findOrFail($id);
        $currentUser = $request->user();

        if ($currentUser->id == $id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($currentUser->role === 'admin' && $currentUser->prodi_id !== $userToDelete->prodi_id) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $userToDelete->delete();

        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus secara permanen.');
    }
}
