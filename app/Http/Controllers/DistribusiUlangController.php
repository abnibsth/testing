<?php

namespace App\Http\Controllers;

use App\Services\JadwalSnapshot;

class DistribusiUlangController extends Controller
{
    /** POST distribusi/{signage}/ulang : kirim ulang semua jadwal satu signage ke TV. */
    public function signage(int $signage)
    {
        $n = JadwalSnapshot::distributeSignage($signage);

        return back()->with('success', $n > 0
            ? "Distribusi ulang selesai. {$n} jadwal diperbarui ke TV."
            : 'Tidak ada perubahan yang perlu didistribusikan.');
    }

    /** POST distribusi/ulang-semua : kirim ulang semua jadwal (milik client yang sedang login). */
    public function semua()
    {
        $n = JadwalSnapshot::distributeAll();

        return back()->with('success', $n > 0
            ? "Distribusi ulang selesai. {$n} jadwal diperbarui ke TV."
            : 'Tidak ada perubahan yang perlu didistribusikan.');
    }
}
