<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_tayang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jadwal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('jadwal_tayang_iklan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_tayang_id')->constrained('jadwal_tayang')->onDelete('cascade');
            $table->foreignId('file_iklan_id')->constrained('file_iklan')->onDelete('cascade');
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_tayang_iklan');
        Schema::dropIfExists('jadwal_tayang');
    }
};
