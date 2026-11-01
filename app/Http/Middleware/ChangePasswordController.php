<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function show()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.min'       => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        $user = auth()->user();

        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password baru tidak boleh sama dengan password sebelumnya.']);
        }

        $user->password = $request->password; // di-hash otomatis oleh cast 'hashed'
        $user->must_change_password = false;
        $user->save();

        // Di portal admin kembali ke daftar client, di dashboard client ke dashboard.
        $tujuan = str_starts_with($request->getHost(), 'admin.') ? 'clients.index' : 'dashboard';

        return redirect()->route($tujuan)->with('success', 'Password berhasil diganti.');
    }
}
