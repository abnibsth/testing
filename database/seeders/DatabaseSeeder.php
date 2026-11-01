<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ModelSignage;
use App\Models\Toko;
use App\Models\DigitalSignage;
use App\Models\FileIklan;
use App\Models\JadwalTayang;
use App\Models\DistribusiJadwal;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ----------------------------------------------------------------
        // Users
        // ----------------------------------------------------------------
        User::create([
            'name'     => 'ADMIN CMS',
            'username' => 'admin',
            'email'    => 'admin@cms.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'aktif'    => true,
        ]);

        User::create([
            'name'     => 'Operator',
            'username' => 'operator',
            'email'    => 'operator@cms.com',
            'password' => Hash::make('password'),
            'role'     => 'operator',
            'aktif'    => true,
        ]);

        // ----------------------------------------------------------------
        // Model Signage (sample dari 87 model di screenshot)
        // ----------------------------------------------------------------
        $modelNames = [
            '22SM3B','22SM3G','27INCHLEDTRANSPARANT','32SM5J','32SM5KC',
            '32SM5KD','32SM5KE','43SM5KD','43SM5KE','43UH5J',
            '49SM5B','49SM5D','55SM5B','55SM5D','65SM5B',
            '65SM5D','75SM5D','86BH5F','VIDEOTRON','VIDEOTRON KACA',
        ];

        $modelMap = [];
        foreach ($modelNames as $name) {
            $modelMap[$name] = ModelSignage::create(['nama_model_tv' => $name]);
        }

        // ----------------------------------------------------------------
        // Toko
        // ----------------------------------------------------------------
        $tokoMajesty = Toko::create([
            'nama_toko'    => 'MAJESTY TG SCBD',
            'kode_toko'    => 'MJTG',
            'channel'      => 'TOPGOLF INDONESIA',
            'alamat'       => 'Didalam Toko Tg',
            'kota'         => 'Jakarta Pusat',
            'provinsi'     => 'DKI Jakarta',
            'area'         => 'JAKARTA PUSAT',
            'sim_card'     => 'internettoko',
            'telepon'      => 'grupwa',
            'jam_buka'     => '10:00:00',
            'jam_tutup'    => '22:00:00',
            'area_teknisi' => 'Jakarta',
            'latitude'     => -6.2297,
            'longitude'    => 106.8077,
        ]);

        $tokoBajuBaru = Toko::create([
            'nama_toko'    => 'BAJU BARU',
            'kode_toko'    => 'MLC01',
            'channel'      => 'TOPGOLF INDONESIA',
            'alamat'       => 'Mangkuluhur City Office Tower One, Jl.gatot Subroto No.kav 1 Lt.18, Karet Semanggi, Setia Budi',
            'kota'         => 'Jakarta Selatan',
            'provinsi'     => 'DKI Jakarta',
            'area'         => 'JAKARTA SELATAN',
            'sim_card'     => 'INTERRNETTOKKO',
            'telepon'      => '085714473742',
            'jam_buka'     => '09:00:00',
            'jam_tutup'    => '18:00:00',
            'area_teknisi' => 'Jakarta',
            'latitude'     => -6.2241,
            'longitude'    => 106.8229,
        ]);

        $tokoTopgolf = Toko::create([
            'nama_toko'    => 'TOPGOLF SCBD',
            'kode_toko'    => 'TOPGOLF',
            'channel'      => 'TOPGOLF INDONESIA',
            'alamat'       => 'Scbd',
            'kota'         => 'Jakarta Pusat',
            'provinsi'     => 'DKI Jakarta',
            'area'         => 'JAKARTA PUSAT',
            'sim_card'     => 'KONEKSIMENGGUNAKANWIFITOKOSAJA',
            'telepon'      => 'TIDAKADAAKANDIUPDATEKEMBALIII',
            'jam_buka'     => '10:00:00',
            'jam_tutup'    => '22:00:00',
            'area_teknisi' => 'Jakarta',
            'latitude'     => -6.2297,
            'longitude'    => 106.8077,
        ]);

        // ----------------------------------------------------------------
        // Digital Signage
        // ----------------------------------------------------------------
        $signageMajesty = DigitalSignage::create([
            'toko_id'          => $tokoMajesty->id,
            'model_signage_id' => $modelMap['VIDEOTRON']->id,
            'nama_signage'     => 'MAJESTY TG 1024X1216',
            'device_id'        => '561689FEA88B8F6D',
            'serial_number'    => '561689FEA88B8F6D',
            'resolusi_width'   => 1920,
            'resolusi_height'  => 1280,
            'orientasi'        => 'Landscape Kiri',
            'tanggal_aktivasi' => '2025-03-13',
            'versi_konten'     => '20230510',
            'status_konten'    => 'Sudah Terbaru',
            'unduh_terakhir'   => '2026-02-26 16:14:14',
            'capture_terakhir' => '2026-04-25 19:25:26',
            'status'           => 'Aktif',
        ]);

        $signageTopgolf = DigitalSignage::create([
            'toko_id'          => $tokoTopgolf->id,
            'model_signage_id' => $modelMap['VIDEOTRON KACA']->id,
            'nama_signage'     => 'VIDEOTRON KACA',
            'device_id'        => 'ADBF8A63B765EBA5',
            'serial_number'    => 'ADBF8A63B765EBA5',
            'resolusi_width'   => 2876,
            'resolusi_height'  => 718,
            'orientasi'        => 'Landscape Kiri',
            'tanggal_aktivasi' => '2025-04-17',
            'versi_konten'     => '20250304',
            'status_konten'    => 'Sudah Terbaru',
            'unduh_terakhir'   => '2026-04-21 16:21:24',
            'capture_terakhir' => '2026-04-25 19:27:05',
            'status'           => 'Aktif',
        ]);

        // ----------------------------------------------------------------
        // File Iklan (matching the 28 entries from screenshot page 1)
        // ----------------------------------------------------------------
        $iklanData = [
            ['DISPLAY VIDEOTRON FACADE SCBD - NEW PRESTIGIO KACA', 6,  30],
            ['DISPLAY VIDEOTRON FACADE SCBD - TAYLORMADE QI4D',    18, 54],
            ['MAJESTY PRESTIGIO 14 - LED AT MAJESTY CORNER',       6,  31],
            ['VIDEO DISPLAY PRIME BX2 RENDER KACA',                20, 30],
            ['VIDEO DISPLAY PRIME BX2 RENDER',                     13, 30],
            ['PRGR DISPLAY VIDEOTRON PREMIER-090126',              4,  15],
            ['TOP GOLF YEAR END DEALS VT KACA',                    8,  12],
            ['LUXURY MAJESTY',                                      16, 48],
            ['MAJESTY LUXURY LAUNCH KACA',                         61, 145],
            ['VIDEOTRON MAJESTIC 02DES 1024X1216',                 35, 87],
            ['PING G440',                                          10, 30],
            ['TOPgolf WAAC 3 MARET 2025',                          12, 30],
            ['Mjupdate160525 FINALL',                              8,  25],
            ['Majesty TopGolf 130325',                             9,  30],
            ['Display Videotron Premier-090126',                   4,  15],
            ['Prgr Display Videotron Premier-090126',              5,  20],
        ];

        $fileIklanRecords = [];
        foreach ($iklanData as [$nama, $ukuranMb, $durasi]) {
            $fileIklanRecords[] = FileIklan::create([
                'pelanggan'    => 'TOPGOLF INDONESIA',
                'tipe'         => 'CONTENT',
                'nama_iklan'   => strtoupper($nama),
                'ukuran_bytes' => $ukuranMb * 1024 * 1024,
                'durasi_detik' => $durasi,
                'has_video'    => true,
                'aktif'        => true,
            ]);
        }

        // ----------------------------------------------------------------
        // Jadwal Tayang
        // ----------------------------------------------------------------
        $jadwalMajesty = JadwalTayang::create([
            'nama_jadwal' => 'VT MAJESTY KEMBAR 240226',
        ]);
        // 3 videos
        $jadwalMajesty->fileIklan()->attach([
            $fileIklanRecords[2]->id  => ['urutan' => 1],
            $fileIklanRecords[12]->id => ['urutan' => 2],
            $fileIklanRecords[13]->id => ['urutan' => 3],
        ]);

        $jadwalTopgolf = JadwalTayang::create([
            'nama_jadwal' => 'VT TOP GOLF KACA-210426',
        ]);
        // 6 videos
        $jadwalTopgolf->fileIklan()->attach([
            $fileIklanRecords[10]->id => ['urutan' => 1],
            $fileIklanRecords[0]->id  => ['urutan' => 2],
            $fileIklanRecords[14]->id => ['urutan' => 3],
            $fileIklanRecords[1]->id  => ['urutan' => 4],
            $fileIklanRecords[15]->id => ['urutan' => 5],
            $fileIklanRecords[11]->id => ['urutan' => 6],
        ]);

        // ----------------------------------------------------------------
        // Distribusi Jadwal
        // ----------------------------------------------------------------
        DistribusiJadwal::create([
            'digital_signage_id' => $signageMajesty->id,
            'jadwal_tayang_id'   => $jadwalMajesty->id,
            'tanggal_mulai'      => '2026-02-24',
            'tanggal_selesai'    => '2027-12-31',
            'waktu_mulai'        => '00:00:00',
            'waktu_selesai'      => '23:59:59',
            'frekuensi_hari'     => 'Setiap Hari',
            'frekuensi_tanggal'  => 'Semua Tanggal',
            'frekuensi_bulan'    => 'Setiap Bulan',
            'aktif'              => true,
        ]);

        DistribusiJadwal::create([
            'digital_signage_id' => $signageTopgolf->id,
            'jadwal_tayang_id'   => $jadwalTopgolf->id,
            'tanggal_mulai'      => '2026-04-21',
            'tanggal_selesai'    => '2027-12-31',
            'waktu_mulai'        => '00:00:00',
            'waktu_selesai'      => '23:59:59',
            'frekuensi_hari'     => 'Setiap Hari',
            'frekuensi_tanggal'  => 'Semua Tanggal',
            'frekuensi_bulan'    => 'Setiap Bulan',
            'aktif'              => true,
        ]);
    }
}
