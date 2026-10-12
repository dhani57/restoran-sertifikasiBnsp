<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk membuat tabel pesanan.
 *
 * Tabel ini menyimpan data pesanan meja restoran dengan relasi
 * ke tabel pelanggan dan meja (lihat PRD Bagian 3.1 dan 3.2).
 */
return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel pesanan.
     */
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->cascadeOnDelete();
            $table->foreignId('meja_id')->constrained('meja')->cascadeOnDelete();
            $table->date('tanggal');                          // Tanggal kedatangan
            $table->time('jam');                               // Jam kedatangan
            $table->integer('jumlah_orang');                   // Jumlah tamu
            $table->text('catatan')->nullable();               // Catatan pesanan atau permintaan khusus
            $table->string('status', 20)->default('dipesan');  // Status: dipesan, selesai, dibatalkan
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration (hapus tabel pesanan).
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
