<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk membuat tabel pelanggan.
 *
 * Tabel ini menyimpan data pelanggan restoran yang terdiri dari
 * nama dan nomor telepon (lihat PRD Bagian 3.1).
 */
return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel pelanggan.
     */
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);        // Nama pelanggan, wajib diisi
            $table->string('no_telepon', 20);    // Nomor telepon pelanggan, wajib diisi
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration (hapus tabel pelanggan).
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
