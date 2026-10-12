<?php

namespace Database\Seeders;

use App\Models\Meja;
use Illuminate\Database\Seeder;

/**
 * Seeder untuk mengisi data contoh meja restoran.
 *
 * Membuat 10 meja (M01–M10) dengan kapasitas bervariasi (2–8 kursi)
 * agar Admin dapat langsung menguji fitur pemesanan setelah migrasi.
 */
class MejaSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk mengisi tabel meja.
     */
    public function run(): void
    {
        $daftarMeja = [
            ['nomor_meja' => 'M01', 'kapasitas' => 2],
            ['nomor_meja' => 'M02', 'kapasitas' => 2],
            ['nomor_meja' => 'M03', 'kapasitas' => 4],
            ['nomor_meja' => 'M04', 'kapasitas' => 4],
            ['nomor_meja' => 'M05', 'kapasitas' => 4],
            ['nomor_meja' => 'M06', 'kapasitas' => 6],
            ['nomor_meja' => 'M07', 'kapasitas' => 6],
            ['nomor_meja' => 'M08', 'kapasitas' => 8],
            ['nomor_meja' => 'M09', 'kapasitas' => 8],
            ['nomor_meja' => 'M10', 'kapasitas' => 10],
        ];

        foreach ($daftarMeja as $meja) {
            Meja::firstOrCreate(['nomor_meja' => $meja['nomor_meja']], $meja);
        }
    }
}
