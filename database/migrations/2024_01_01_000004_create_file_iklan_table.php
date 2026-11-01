<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_iklan', function (Blueprint $table) {
            $table->id();
            $table->string('pelanggan');
            $table->enum('tipe', ['CONTENT', 'BUMPER', 'PROMO'])->default('CONTENT');
            $table->string('nama_iklan');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->bigInteger('ukuran_bytes')->nullable();
            $table->integer('durasi_detik')->nullable();
            $table->boolean('has_video')->default(false);
            $table->text('catatan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_iklan');
    }
};
