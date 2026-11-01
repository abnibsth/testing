<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribusi_jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('digital_signage_id')->constrained('digital_signage')->onDelete('cascade');
            $table->foreignId('jadwal_tayang_id')->constrained('jadwal_tayang')->onDelete('cascade');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->time('waktu_mulai')->default('00:00:00');
            $table->time('waktu_selesai')->default('23:59:59');
            $table->enum('frekuensi_hari', ['Setiap Hari', 'Senin-Jumat', 'Sabtu-Minggu', 'Custom'])->default('Setiap Hari');
            $table->enum('frekuensi_tanggal', ['Semua Tanggal', 'Tanggal Tertentu'])->default('Semua Tanggal');
            $table->enum('frekuensi_bulan', ['Setiap Bulan', 'Bulan Tertentu'])->default('Setiap Bulan');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribusi_jadwal');
    }
};
