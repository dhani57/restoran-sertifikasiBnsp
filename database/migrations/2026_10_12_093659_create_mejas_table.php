<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk membuat tabel meja.
 *
 * Tabel ini menyimpan data meja restoran yang terdiri dari
 * nomor meja (unik) dan kapasitas kursi (lihat PRD Bagian 3.1).
 */
return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel meja.
     */
    public function up(): void
    {
        Schema::create('meja', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_meja', 10)->unique();  // Nomor meja unik, contoh: M01
            $table->integer('kapasitas');                 // Jumlah kursi pada meja
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration (hapus tabel meja).
     */
    public function down(): void
    {
        Schema::dropIfExists('meja');
    }
};
