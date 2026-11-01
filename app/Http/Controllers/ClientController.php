<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('id')->get();
        $count = fn ($table) => DB::table($table)->select('client_id', DB::raw('COUNT(*) as c'))
            ->groupBy('client_id')->pluck('c', 'client_id');

        return view('clients.index', [
            'clients' => $clients,
            'users'   => $count('users'),
            'toko'    => $count('toko'),
            'signage' => $count('digital_signage'),
        ]);
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        // Subdomain dinormalkan; kosong = dibuat otomatis dari kode client.
        $request->merge(['subdomain' => strtolower(trim((string) $request->input('subdomain'))) ?: null]);

        $v = $request->validate([
            'nama'           => 'required|string|max:255',
            'kode'           => 'required|alpha_dash|max:50|unique:clients,kode',
            'subdomain'      => ['nullable', 'unique:clients,subdomain', function ($attribute, $value, $fail) {
                if ($value && !Client::subdomainValid($value)) {
                    $fail('Subdomain hanya boleh huruf kecil, angka, dan tanda hubung (3-30 karakter), serta tidak memakai nama yang dicadangkan.');
                }
            }],
            'admin_name'     => 'required|string|max:255',
            'admin_username' => 'required|alpha_dash|max:50|unique:users,username',
            'admin_email'    => 'required|email|max:255|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ]);

        $client = null;

        DB::transaction(function () use ($v, &$client) {
            $client = Client::create([
                'nama'      => $v['nama'],
                'kode'      => strtolower($v['kode']),
                'subdomain' => $v['subdomain'] ?? Client::buatSubdomain($v['kode']),
                'aktif'     => true,
            ]);

            // password di-hash otomatis oleh cast 'hashed' pada model User
            User::create([
                'name'      => $v['admin_name'],
                'username'  => $v['admin_username'],
                'email'     => $v['admin_email'],
                'password'  => $v['admin_password'],
                'role'      => 'admin',
                'aktif'     => 1,
                'client_id' => $client->id,
            ]);
        });

        return redirect()->route('clients.index')->with('success', 'Client dan akun admin berhasil dibuat. Alamat dashboard: ' . $client->url());
    }

    public function toggle(Client $client)
    {
        if ($client->id == 1) {
            return back()->with('error', 'Client default tidak boleh dinonaktifkan.');
        }
        $client->update(['aktif' => !$client->aktif]);
        return back()->with('success', 'Status client diperbarui.');
    }

    /**
     * Hapus client BESERTA seluruh datanya: user, toko, signage, jadwal, distribusi, file iklan
     * (termasuk file video fisik). Wajib mengetik kode client sebagai konfirmasi.
     * Client default (id 1) tidak bisa dihapus. TV milik client ini akan berhenti menerima jadwal.
     */
    public function destroy(Request $request, Client $client)
    {
        if ($client->id == 1) {
            return back()->with('error', 'Client default tidak boleh dihapus.');
        }

        $konfirmasi = strtolower(trim((string) $request->input('konfirmasi')));
        if ($konfirmasi === '' || $konfirmasi !== strtolower($client->kode)) {
            return back()->with('error', 'Kode konfirmasi tidak cocok. Client tidak dihapus.');
        }

        $cid       = (int) $client->id;
        $nama      = $client->nama;
        $filePaths = [];
        $ringkasan = [];

        try {
            // Urutan hapus antar tabel tidak jadi masalah; semua dilakukan dalam satu transaksi.
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            DB::transaction(function () use ($cid, &$filePaths, &$ringkasan) {
                $punyaClientId = fn ($tabel) => Schema::hasTable($tabel) && Schema::hasColumn($tabel, 'client_id');

                $jadwalIds = $punyaClientId('jadwal_tayang')
                    ? DB::table('jadwal_tayang')->where('client_id', $cid)->pluck('id')->all() : [];
                $fileRows  = $punyaClientId('file_iklan')
                    ? DB::table('file_iklan')->where('client_id', $cid)->get(['id', 'file_path']) : collect();
                $fileIds   = $fileRows->pluck('id')->all();
                $filePaths = $fileRows->pluck('file_path')->filter()->values()->all();
                $userIds   = $punyaClientId('users')
                    ? DB::table('users')->where('client_id', $cid)->pluck('id')->all() : [];

                // 1) Tabel penghubung (tidak punya client_id): hapus berdasarkan id jadwal / file milik client.
                foreach (['jadwal_file_iklan', 'file_iklan_jadwal_tayang', 'jadwal_tayang_iklan'] as $pivot) {
                    if (!Schema::hasTable($pivot)) {
                        continue;
                    }
                    $kondisi = ['jadwal_tayang_id' => $jadwalIds, 'file_iklan_id' => $fileIds, 'iklan_id' => $fileIds];
                    $q = DB::table($pivot)->where(function ($w) use ($pivot, $kondisi) {
                        $ada = false;
                        foreach ($kondisi as $kolom => $ids) {
                            if ($ids && Schema::hasColumn($pivot, $kolom)) {
                                $w->orWhereIn($kolom, $ids);
                                $ada = true;
                            }
                        }
                        if (!$ada) {
                            $w->whereRaw('1 = 0');
                        }
                    });
                    $n = $q->delete();
                    if ($n) {
                        $ringkasan[$pivot] = $n;
                    }
                }

                // 2) Sesi login user client (bila sesi disimpan di database)
                if ($userIds && Schema::hasTable('sessions') && Schema::hasColumn('sessions', 'user_id')) {
                    DB::table('sessions')->whereIn('user_id', $userIds)->delete();
                }

                // 3) Semua tabel yang punya kolom client_id (ditemukan otomatis lewat SHOW TABLES,
                //    tidak bergantung pada DATABASE() / information_schema).
                $tabelClient = [];
                foreach (DB::select("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'") as $row) {
                    $nama = array_values((array) $row)[0];
                    if ($nama !== 'clients' && Schema::hasColumn($nama, 'client_id')) {
                        $tabelClient[] = $nama;
                    }
                }

                // Pengaman: tabel inti harus ikut terdeteksi, bila tidak batalkan semuanya (rollback).
                foreach (['users', 'digital_signage', 'toko'] as $wajib) {
                    if (!in_array($wajib, $tabelClient, true)) {
                        throw new \RuntimeException("Tabel $wajib dengan kolom client_id tidak terdeteksi; penghapusan dibatalkan.");
                    }
                }

                foreach ($tabelClient as $tabel) {
                    $n = DB::table($tabel)->where('client_id', $cid)->delete();
                    if ($n) {
                        $ringkasan[$tabel] = $n;
                    }
                }

                // 4) Terakhir: client itu sendiri
                DB::table('clients')->where('id', $cid)->delete();
            });
        } catch (\Throwable $e) {
            Log::error('Hapus client gagal', ['client_id' => $cid, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menghapus client, tidak ada data yang berubah: ' . $e->getMessage());
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // File video fisik dihapus SETELAH data berhasil dihapus (bila transaksi gagal, file tetap aman).
        $base      = rtrim((string) config('media.path'), '/');
        $baseReal  = $base !== '' ? realpath($base) : false;
        $terhapus  = 0;
        $gagal     = 0;

        if ($baseReal) {
            foreach ($filePaths as $rel) {
                $real = realpath($base . '/' . ltrim($rel, '/'));
                // hanya hapus file yang benar-benar berada di dalam folder media
                if ($real && is_file($real) && strpos($real, $baseReal . DIRECTORY_SEPARATOR) === 0) {
                    @unlink($real) ? $terhapus++ : $gagal++;
                }
            }
            // rapikan folder client yang sudah kosong (rmdir hanya berhasil bila folder kosong)
            foreach ([$base . '/' . $cid . '/iklan', $base . '/' . $cid] as $dir) {
                if (is_dir($dir)) {
                    @rmdir($dir);
                }
            }
        }

        Log::info('Client dihapus', ['client_id' => $cid, 'nama' => $nama, 'ringkasan' => $ringkasan, 'file_terhapus' => $terhapus, 'file_gagal' => $gagal]);

        $detail = [];
        foreach ($ringkasan as $tabel => $n) {
            $detail[] = "$n baris $tabel";
        }
        $pesan = "Client \"$nama\" dan seluruh datanya telah dihapus";
        $pesan .= $detail ? ' (' . implode(', ', $detail) . ')' : '';
        $pesan .= ". File video terhapus: $terhapus" . ($gagal ? ", gagal dihapus: $gagal (cek izin folder media)" : '') . '.';

        return redirect()->route('clients.index')->with('success', $pesan);
    }
}
