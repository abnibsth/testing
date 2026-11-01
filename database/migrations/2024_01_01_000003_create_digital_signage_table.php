<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('digital_signage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toko_id')->constrained('toko')->onDelete('cascade');
            $table->foreignId('model_signage_id')->nullable()->constrained('model_signage')->nullOnDelete();
            $table->string('nama_signage');
            $table->string('device_id')->unique();
            $table->string('serial_number')->unique();
            $table->integer('resolusi_width')->default(1920);
            $table->integer('resolusi_height')->default(1080);
            $table->enum('orientasi', ['Landscape Kiri', 'Landscape Kanan', 'Portrait'])->default('Landscape Kiri');
            $table->date('tanggal_aktivasi')->nullable();
            $table->string('versi_konten')->nullable();
            $table->enum('status_konten', ['Sudah Terbaru', 'Belum Terbaru', 'Tidak Diketahui'])->default('Tidak Diketahui');
            $table->datetime('unduh_terakhir')->nullable();
            $table->datetime('capture_terakhir')->nullable();
            $table->string('capture_path')->nullable();
            $table->enum('status', ['Aktif', 'Nonaktif', 'Maintenance'])->default('Aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_signage');
    }
};
