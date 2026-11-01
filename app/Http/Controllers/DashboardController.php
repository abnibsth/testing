<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Default values
        $statsPerArea = collect();
        $chartData    = collect();
        $totalToko    = 0;
        $totalSignage = 0;
        $sudahUpdate  = 0;
        $belumUpdate  = 0;
        $tidakDiketahui = 0; 
        
        // Variabel untuk Status Jaringan (Online/Offline)
        $signageOnline = 0;
        $signageOffline = 0;

        // 🔒 Multi-tenant: query manual (DB::table) tidak terkena global scope, filter client_id di sini
        $cid = \App\Support\Tenant::id() ?? 0;
        $ds   = fn () => DB::table('digital_signage')->where('client_id', $cid);

        try {
            // 1. Hitung Total Toko
            $totalToko = DB::table('toko')->where('client_id', $cid)->count();

            // 2. Hitung Signage dan Status Konten
            if (DB::getSchemaBuilder()->hasTable('digital_signage')) {
                $totalSignage = $ds()->count();
                
                $columns = DB::getSchemaBuilder()->getColumnListing('digital_signage');
                
                // --- KONTEN STATUS UPDATE ---
                if (in_array('status_konten', $columns)) {
                    $sudahUpdate = $ds()->where('status_konten', 'Sudah Terbaru')->count();
                    $belumUpdate = $ds()->where('status_konten', 'Belum Terbaru')->count();
                    
                    $tidakDiketahui = $ds()
                        ->where(function($query) {
                            $query->whereNull('status_konten')
                                  ->orWhere('status_konten', '')
                                  ->orWhere('status_konten', 'Tidak Diketahui')
                                  ->orWhereNotIn('status_konten', ['Sudah Terbaru', 'Belum Terbaru']);
                        })
                        ->count();
                }

                // --- 🎯 SINKRONISASI REALTIME STATUS KONEKSI (KONEKSI_STATUS) ---
                if (in_array('koneksi_status', $columns)) {
                    
                    // Ambil seluruh data signage untuk mengecek interval waktu pembaruan
                    $allSignages = $ds()->get();
                    
                    foreach ($allSignages as $signage) {
                        // Cek timestamps kapan perangkat terakhir memperbarui datanya ke server
                        $waktuTerakhir = strtotime($signage->updated_at ?? $signage->created_at);
                        $waktuSekarang = time();
                        $selisihMenit  = ($waktuSekarang - $waktuTerakhir) / 60;

                        // TOLERANSI REATIME: Jika lebih dari 3 menit tidak ada tanda-tanda interaksi,
                        // paksa status koneksinya berubah menjadi 'Offline' di MySQL fisik.
                        if ($selisihMenit > 10) {
                            DB::table('digital_signage')
                                ->where('id', $signage->id)
                                ->update(['koneksi_status' => 'Offline']);
                        }
                    }

                    // Hitung data yang BENAR-BENAR bertuliskan 'online' murni (mengabaikan spasi & kapital)
                    $signageOnline = $ds()
                        ->whereRaw('LOWER(TRIM(koneksi_status)) = ?', ['online'])
                        ->count();
                    
                    // Hitung data yang BENAR-BENAR bertuliskan 'offline' murni (mengabaikan spasi & kapital)
                    $signageOffline = $ds()
                        ->where(function ($q) {
                            $q->whereRaw('LOWER(TRIM(koneksi_status)) = ?', ['offline'])
                              ->orWhereNull('koneksi_status');
                        })
                        ->count();
                        
                    // Sistem Penyelaras Keamanan (jika ada text string ghaib/kosong di database)
                    $totalTerhitung = $signageOnline + $signageOffline;
                    if ($totalTerhitung < $totalSignage) {
                        $signageOffline += ($totalSignage - $totalTerhitung);
                    }
                }
            }

            // 3. Query Area & Statistik Berdasarkan Area Toko
            if (DB::getSchemaBuilder()->hasTable('toko') && DB::getSchemaBuilder()->hasTable('digital_signage')) {
                $statsPerArea = DB::table('toko')
                    ->where('toko.client_id', $cid)
                    ->leftJoin('digital_signage as ds', 'toko.id', '=', 'ds.toko_id')
                    ->select(
                        'toko.area',
                        DB::raw('COUNT(DISTINCT toko.id) as total_toko'),
                        DB::raw('SUM(CASE WHEN ds.status_konten = "Sudah Terbaru" THEN 1 ELSE 0 END) as sudah_update'),
                        DB::raw('SUM(CASE WHEN ds.status_konten = "Belum Terbaru" THEN 1 ELSE 0 END) as belum_update'),
                        DB::raw('SUM(CASE WHEN ds.status_konten IS NULL OR ds.status_konten NOT IN ("Sudah Terbaru", "Belum Terbaru") THEN 1 ELSE 0 END) as tidak_diketahui'),
                        DB::raw('SUM(CASE WHEN ds.tanggal_aktivasi >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as baru_aktivasi')
                    )
                    ->groupBy('toko.area')
                    ->get();

                $chartData = $statsPerArea->map(fn($item) => [
                    'area'  => $item->area,
                    'total' => (int) $item->total_toko,
                ])->values();
            }

        } catch (\Exception $e) {
            Log::error("Dashboard Load Error: " . $e->getMessage());
        }

        // Kirim seluruh variabel data yang telah disinkronkan ke View Blade
        return view('dashboard.index', compact(
            'statsPerArea',
            'totalToko',
            'totalSignage',
            'sudahUpdate',
            'belumUpdate',
            'tidakDiketahui', 
            'chartData',
            'signageOnline',   
            'signageOffline'   
        ));
    }
}