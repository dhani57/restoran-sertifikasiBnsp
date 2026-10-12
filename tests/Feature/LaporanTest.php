<?php

namespace Tests\Feature;

use App\Models\Meja;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pengujian fitur laporan pemesanan dan cetak PDF (KF-05, PRD Bagian 4.5).
 */
class LaporanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Halaman laporan dapat diakses dan menampilkan filter.
     */
    public function test_halaman_laporan_dapat_diakses(): void
    {
        $response = $this->get('/laporan');

        $response->assertStatus(200);
        $response->assertSee('Laporan Pemesanan');
    }

    /**
     * Filter laporan menampilkan data pesanan sesuai rentang tanggal.
     */
    public function test_filter_laporan_menampilkan_pesanan_sesuai_rentang_tanggal(): void
    {
        $pelanggan = Pelanggan::create(['nama' => 'Siti', 'no_telepon' => '08111222333']);
        $meja = Meja::create(['nomor_meja' => 'M02', 'kapasitas' => 4]);

        Pesanan::create([
            'pelanggan_id' => $pelanggan->id,
            'meja_id' => $meja->id,
            'tanggal' => '2026-11-05',
            'jam' => '18:00',
            'jumlah_orang' => 3,
            'status' => 'dipesan',
        ]);

        $response = $this->get('/laporan?tanggal_awal=2026-11-01&tanggal_akhir=2026-11-10');

        $response->assertStatus(200);
        $response->assertSee('Siti');
        $response->assertSee('M02');
    }

    /**
     * Cetak laporan menghasilkan unduhan file PDF.
     */
    public function test_cetak_laporan_pdf_berhasil(): void
    {
        $pelanggan = Pelanggan::create(['nama' => 'Rina', 'no_telepon' => '08222333444']);
        $meja = Meja::create(['nomor_meja' => 'M03', 'kapasitas' => 2]);

        Pesanan::create([
            'pelanggan_id' => $pelanggan->id,
            'meja_id' => $meja->id,
            'tanggal' => '2026-11-05',
            'jam' => '12:00',
            'jumlah_orang' => 2,
            'status' => 'selesai',
        ]);

        $response = $this->get('/laporan/cetak?tanggal_awal=2026-11-01&tanggal_akhir=2026-11-10');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
