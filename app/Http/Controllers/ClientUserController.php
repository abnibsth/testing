<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * Daftar user per client dan reset password (hanya superadmin, di portal admin).
 */
class ClientUserController extends Controller
{
    public function index(Client $client)
    {
        $users = User::where('client_id', $client->id)->orderBy('name')->get();

        return view('clients.users', compact('client', 'users'));
    }

    public function reset(Client $client, User $user)
    {
        // User harus milik client ini, dan akun sendiri tidak boleh direset dari sini.
        abort_unless((int) $user->client_id === (int) $client->id, 404);
        abort_if($user->id === auth()->id(), 403, 'Gunakan halaman ganti password untuk akun sendiri.');

        $sementara = self::passwordSementara();

        $user->password = $sementara; // di-hash otomatis oleh cast 'hashed' pada model User
        $user->must_change_password = true;
        $user->setRememberToken(Str::random(60));
        $user->save();

        return redirect()->route('clients.users', $client)->with('reset_info', [
            'nama'     => $user->name,
            'username' => $user->username,
            'password' => $sementara,
        ]);
    }

    /** Password acak 10 karakter, tanpa karakter yang mudah tertukar (0/O, 1/l/I). */
    private static function passwordSementara(int $panjang = 10): string
    {
        $karakter = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
        $hasil = '';

        for ($i = 0; $i < $panjang; $i++) {
            $hasil .= $karakter[random_int(0, strlen($karakter) - 1)];
        }

        return $hasil;
    }
}
