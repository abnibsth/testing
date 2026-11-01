<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\DistribusiJadwal;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('distribusi_jadwal', function (Blueprint $table) {
            if (!Schema::hasColumn('distribusi_jadwal', 'status_validasi')) {
                $table->string('status_validasi', 30)->default('Menunggu Validasi');
            }
            if (!Schema::hasColumn('distribusi_jadwal', 'snapshot_file_iklan')) {
                // Daftar id file_iklan (berurutan) yang SUDAH divalidasi & boleh tayang di TV
                $table->json('snapshot_file_iklan')->nullable();
            }
            if (!Schema::hasColumn('distribusi_jadwal', 'divalidasi_pada')) {
                $table->dateTime('divalidasi_pada')->nullable();
            }
        });

        // Distribusi yang sudah berjalan dianggap tervalidasi supaya TV tidak tiba-tiba kosong.
        DistribusiJadwal::with('jadwalTayang.fileIklan')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                $ids = $row->jadwalTayang
                    ? $row->jadwalTayang->fileIklan->pluck('id')->map(fn ($v) => (int) $v)->values()->all()
                    : [];

                if (empty($ids)) {
                    continue;
                }

                DB::table('distribusi_jadwal')->where('id', $row->id)->update([
                    'status_validasi'     => 'Tervalidasi',
                    'snapshot_file_iklan' => json_encode($ids),
                    'divalidasi_pada'     => now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('distribusi_jadwal', function (Blueprint $table) {
            $table->dropColumn(['status_validasi', 'snapshot_file_iklan', 'divalidasi_pada']);
        });
    }
};
