<?php

namespace Database\Seeders;

use App\Models\Meja;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use Illuminate\Database\Seeder;

/**
 * Seeder utama yang memanggil seeder lainnya.
 *
 * Perintah: php artisan db:seed
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan seluruh seeder aplikasi.
     */
    public function run(): void
    {
        // 1. Isi tabel meja dengan data contoh (M01-M10)
        $this->call(MejaSeeder::class);

        // 2. Isi data contoh pelanggan
        $budi = Pelanggan::firstOrCreate(
            ['no_telepon' => '081234567890'],
            ['nama' => 'Budi Santoso']
        );
        $siti = Pelanggan::firstOrCreate(
            ['no_telepon' => '082198765432'],
            ['nama' => 'Siti Nurhaliza']
        );
        $andi = Pelanggan::firstOrCreate(
            ['no_telepon' => '085712345678'],
            ['nama' => 'Andi Wijaya']
        );

        // 3. Isi data contoh pesanan untuk pengujian/demo langsung
        $meja1 = Meja::where('nomor_meja', 'M01')->first();
        $meja3 = Meja::where('nomor_meja', 'M03')->first();
        $meja6 = Meja::where('nomor_meja', 'M06')->first();

        $today = date('Y-m-d');

        if ($meja1 && $budi) {
            Pesanan::firstOrCreate(
                ['pelanggan_id' => $budi->id, 'tanggal' => $today, 'jam' => '19:00'],
                [
                    'meja_id' => $meja1->id,
                    'jumlah_orang' => 2,
                    'catatan' => 'Dekat jendela',
                    'status' => 'dipesan',
                ]
            );
        }

        if ($meja3 && $siti) {
            Pesanan::firstOrCreate(
                ['pelanggan_id' => $siti->id, 'tanggal' => $today, 'jam' => '18:30'],
                [
                    'meja_id' => $meja3->id,
                    'jumlah_orang' => 4,
                    'catatan' => 'Ulang tahun anak',
                    'status' => 'dipesan',
                ]
            );
        }

        if ($meja6 && $andi) {
            Pesanan::firstOrCreate(
                ['pelanggan_id' => $andi->id, 'tanggal' => date('Y-m-d', strtotime('-1 day')), 'jam' => '20:00'],
                [
                    'meja_id' => $meja6->id,
                    'jumlah_orang' => 5,
                    'catatan' => 'Menu paket keluarga',
                    'status' => 'selesai',
                ]
            );
        }
    }
}
