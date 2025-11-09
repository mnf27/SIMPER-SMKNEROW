<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan form profil pengguna.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update username login pengguna.
     */
    public function update(Request $request): RedirectResponse
    {
        // Validasi input username
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $request->user()->id],
        ], [
            'username.required' => 'Username tidak boleh kosong.',
            'username.unique' => 'Username sudah digunakan oleh pengguna lain.',
            'username.max' => 'Username terlalu panjang (maksimal 255 karakter).',
        ]);

        // Simpan perubahan username
        $user = $request->user();
        $user->username = $validated['username'];
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}
