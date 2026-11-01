<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('toko', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko');
            $table->string('kode_toko')->unique();
            $table->string('channel');
            $table->string('alamat');
            $table->string('kota');
            $table->string('provinsi')->default('DKI Jakarta');
            $table->string('area')->default('JAKARTA PUSAT');
            $table->string('sim_card')->nullable();
            $table->string('telepon')->nullable();
            $table->time('jam_buka')->default('10:00:00');
            $table->time('jam_tutup')->default('22:00:00');
            $table->string('area_teknisi')->default('Jakarta');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toko');
    }
};
