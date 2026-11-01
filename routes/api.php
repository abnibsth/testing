<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\VideoController;

// 1️⃣ PUBLIC: Registrasi Device (Tidak butuh token)
Route::post('/device/register', [DeviceController::class, 'register'])->name('api.device.register');

// 2️ PROTECTED: Wajib pakai header X-Device-Token
Route::middleware('device.auth')->group(function () {
    Route::get('/device/{device_id}', [DeviceController::class, 'info'])->name('api.device.info');
    Route::post('/device/{device_id}/ping', [DeviceController::class, 'ping'])->name('api.device.ping');
    Route::get('/device/{device_id}/check-update', [DeviceController::class, 'checkUpdate'])->name('api.device.check-update');
    Route::post('/device/{device_id}/sync', [DeviceController::class, 'sync'])->name('api.device.sync');
    Route::post('/device/{device_id}/status', [DeviceController::class, 'updateStatus'])->name('api.device.status');
    Route::get('/device/{device_id}/jadwal', [JadwalController::class, 'getJadwal'])->name('api.device.jadwal');
    Route::get('/video/{file_iklan_id}/download', [VideoController::class, 'download'])->name('api.video.download');
    Route::get('/video/{file_iklan_id}/info', [VideoController::class, 'info'])->name('api.video.info');
});

// 🔒 ADMIN ROUTES (Dikommentari sementara agar tidak trigger error AuthManager)
// Aktifkan kembali SETELAH /device/register berhasil 201
// Route::middleware('auth')->prefix('admin')->group(function () {
//     Route::get('/signage/monitoring', [\App\Http\Controllers\DigitalSignageController::class, 'getMonitoringStatus'])
//          ->name('api.admin.signage.monitoring');
// });