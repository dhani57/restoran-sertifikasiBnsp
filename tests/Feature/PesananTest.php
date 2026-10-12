<?php

namespace Tests\Feature;

use App\Models\Meja;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pengujian unit untuk fitur pesanan (PRD Bagian 4.9).
 *
 * Menggunakan basis data pengujian terpisah (restoran_test) dan
 * trait RefreshDatabase untuk mengosongkan data setiap pengujian.
 */
class PesananTest extends TestCase
{
    use RefreshDatabase;

    /**
     * UT-01: Admin membuat pesanan dengan data valid dan meja tersedia.
     *
     * Hasil yang diharapkan: pesanan tersimpan di basis data dan sistem
     * diarahkan ke daftar pesanan.
     */
    public function test_pesanan_berhasil_disimpan_jika_data_valid_dan_meja_tersedia(): void
    {
        // Siapkan data pelanggan dan meja
        $pelanggan = Pelanggan::create(['nama' => 'Budi', 'no_telepon' => '08123456789']);
        $meja = Meja::create(['nomor_meja' => 'M01', 'kapasitas' => 4]);

        // Kirim permintaan pembuatan pesanan
        $response = $this->post('/pesanan', [
            'pelanggan_id' => $pelanggan->id,
            'tanggal' => '2026-11-01',
            'jam' => '19:00',
            'jumlah_orang' => 2,
            'catatan' => 'Dekat jendela',
        ]);

        // Periksa: pesanan tersimpan dan diarahkan ke daftar pesanan
        $response->assertRedirect(route('pesanan.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('pesanan', 1);
        $this->assertDatabaseHas('pesanan', [
            'pelanggan_id' => $pelanggan->id,
            'meja_id' => $meja->id,
            'tanggal' => '2026-11-01',
            'status' => 'dipesan',
        ]);
    }

    /**
     * UT-02: Admin membuat pesanan pada tanggal dan jam yang meja-nya sudah penuh.
     *
     * Hasil yang diharapkan: pesanan tidak tersimpan dan muncul pesan "meja penuh".
     */
    public function test_pesanan_gagal_jika_meja_penuh(): void
    {
        // Siapkan data: satu meja yang sudah dipesan
        $pelanggan = Pelanggan::create(['nama' => 'Budi', 'no_telepon' => '08123456789']);
        $meja = Meja::create(['nomor_meja' => 'M01', 'kapasitas' => 4]);

        Pesanan::create([
            'pelanggan_id' => $pelanggan->id,
            'meja_id' => $meja->id,
            'tanggal' => '2026-11-01',
            'jam' => '19:00',
            'jumlah_orang' => 2,
            'status' => 'dipesan',
        ]);

        // Kirim permintaan pesanan baru pada waktu yang sama
        $response = $this->post('/pesanan', [
            'pelanggan_id' => $pelanggan->id,
            'tanggal' => '2026-11-01',
            'jam' => '19:00',
            'jumlah_orang' => 2,
        ]);

        // Periksa: pesanan ditolak dengan pesan error
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('pesanan', 1); // tetap 1, tidak bertambah
    }

    /**
     * UT-03: Admin membuat pesanan dengan data tidak lengkap (tanpa tanggal).
     *
     * Hasil yang diharapkan: muncul kesalahan validasi dan pesanan tidak tersimpan.
     */
    public function test_pesanan_gagal_jika_data_tidak_lengkap(): void
    {
        $pelanggan = Pelanggan::create(['nama' => 'Andi', 'no_telepon' => '08987654321']);
        Meja::create(['nomor_meja' => 'M01', 'kapasitas' => 4]);

        // Kirim permintaan tanpa field tanggal
        $response = $this->post('/pesanan', [
            'pelanggan_id' => $pelanggan->id,
            // 'tanggal' tidak dikirim (sengaja)
            'jam' => '19:00',
            'jumlah_orang' => 2,
        ]);

        // Periksa: terdapat error validasi pada field tanggal
        $response->assertSessionHasErrors('tanggal');
        $this->assertDatabaseCount('pesanan', 0); // pesanan tidak tersimpan
    }

    /**
     * KF-04: Admin dapat mengubah status pesanan menjadi selesai atau dibatalkan.
     */
    public function test_ubah_status_pesanan_berhasil(): void
    {
        $pelanggan = Pelanggan::create(['nama' => 'Budi', 'no_telepon' => '08123456789']);
        $meja = Meja::create(['nomor_meja' => 'M01', 'kapasitas' => 4]);

        $pesanan = Pesanan::create([
            'pelanggan_id' => $pelanggan->id,
            'meja_id' => $meja->id,
            'tanggal' => '2026-11-01',
            'jam' => '19:00',
            'jumlah_orang' => 2,
            'status' => 'dipesan',
        ]);

        $response = $this->put("/pesanan/{$pesanan->id}", [
            'pelanggan_id' => $pelanggan->id,
            'tanggal' => '2026-11-01',
            'jam' => '19:00',
            'jumlah_orang' => 2,
            'status' => 'selesai',
        ]);

        $response->assertRedirect(route('pesanan.index'));
        $this->assertDatabaseHas('pesanan', [
            'id' => $pesanan->id,
            'status' => 'selesai',
        ]);
    }

    /**
     * Admin dapat menghapus pesanan.
     */
    public function test_hapus_pesanan_berhasil(): void
    {
        $pelanggan = Pelanggan::create(['nama' => 'Budi', 'no_telepon' => '08123456789']);
        $meja = Meja::create(['nomor_meja' => 'M01', 'kapasitas' => 4]);

        $pesanan = Pesanan::create([
            'pelanggan_id' => $pelanggan->id,
            'meja_id' => $meja->id,
            'tanggal' => '2026-11-01',
            'jam' => '19:00',
            'jumlah_orang' => 2,
            'status' => 'dipesan',
        ]);

        $response = $this->delete("/pesanan/{$pesanan->id}");

        $response->assertRedirect(route('pesanan.index'));
        $this->assertDatabaseMissing('pesanan', [
            'id' => $pesanan->id,
        ]);
    }
}
