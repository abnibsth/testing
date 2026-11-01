<?php

namespace App\Http\Middleware;

use App\Models\DigitalSignage;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeviceAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Device-Token');

        if (!$token) {
            return response()->json([
                'success'     => false,
                'message'     => 'Device token tidak ditemukan. Sertakan header X-Device-Token.',
                'error_code'  => 'TOKEN_MISSING',
                're_register' => true,
            ], 401);
        }

        $device = DigitalSignage::where('device_token', $token)->first();

        // Perangkat dihapus lalu ditambahkan lagi: baris baru belum punya token.
        // Token lama dari APK diadopsi ke baris itu, asalkan device_id di URL cocok
        // dan baris tersebut memang belum punya token.
        if (!$device && strlen($token) >= 32) {
            $routeDeviceId = $request->route('device_id');

            if ($routeDeviceId) {
                $candidate = DigitalSignage::where('device_id', $routeDeviceId)
                    ->where(function ($q) {
                        $q->whereNull('device_token')->orWhere('device_token', '');
                    })
                    ->first();

                if ($candidate) {
                    $candidate->device_token = $token;
                    $candidate->save();
                    $device = $candidate;
                }
            }
        }

        if (!$device) {
            return response()->json([
                'success'     => false,
                'message'     => 'Device token tidak valid.',
                'error_code'  => 'TOKEN_INVALID',
                're_register' => true,   // aplikasi TV sebaiknya memanggil POST /api/device/register lagi
            ], 401);
        }

        if ($device->status === 'Nonaktif') {
            return response()->json([
                'success'    => false,
                'message'    => 'Perangkat dinonaktifkan oleh admin.',
                'error_code' => 'DEVICE_INACTIVE',
            ], 403);
        }

        $request->merge(['_device' => $device]);

        return $next($request);
    }
}
