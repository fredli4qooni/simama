<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validatedData = $request->validated();

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $fillData = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
        ];

        $isMahasiswa = $user && !in_array($user->role, ['admin', 'dpl', 'admin_fakultas']);
        if ($isMahasiswa) {
            if (array_key_exists('total_sks', $validatedData)) {
                $fillData['total_sks'] = $validatedData['total_sks'];
            }
            if (array_key_exists('ipk', $validatedData)) {
                $fillData['ipk'] = $validatedData['ipk'];
            }
            if (array_key_exists('semester', $validatedData)) {
                $fillData['semester'] = $validatedData['semester'];
            }
        }

        if (array_key_exists('nim', $validatedData)) {
            $fillData['nim'] = $validatedData['nim'];
        }

        if (array_key_exists('nip', $validatedData)) {
            $fillData['nip'] = $validatedData['nip'];
        }

        $user->fill($fillData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated')->with('success', 'Profil dan identitas berhasil diperbarui.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
