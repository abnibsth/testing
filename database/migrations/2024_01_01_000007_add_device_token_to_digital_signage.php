<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('digital_signage', function (Blueprint $table) {
            $table->string('device_token', 64)->nullable()->unique()->after('device_id');
            $table->string('device_model')->nullable()->after('device_token');
            $table->string('android_version')->nullable()->after('device_model');
            $table->string('app_version')->nullable()->after('android_version');
            $table->datetime('last_ping')->nullable()->after('app_version');
            $table->enum('koneksi_status', ['online', 'offline'])->default('offline')->after('last_ping');
        });
    }

    public function down(): void
    {
        Schema::table('digital_signage', function (Blueprint $table) {
            $table->dropColumn([
                'device_token',
                'device_model',
                'android_version',
                'app_version',
                'last_ping',
                'koneksi_status',
            ]);
        });
    }
};