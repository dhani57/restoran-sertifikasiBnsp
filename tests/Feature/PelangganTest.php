<?php

namespace Tests\Feature;

use App\Models\Pelanggan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pengujian CRUD Pelanggan (KF-01, PRD Bagian 2.2).
 */
class PelangganTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Halaman daftar pelanggan dapat diakses.
     */
    public function test_halaman_pelanggan_dapat_diakses(): void
    {
        $response = $this->get('/pelanggan');

        $response->assertStatus(200);
        $response->assertSee('Daftar Pelanggan');
    }

    /**
     * Pelanggan baru dapat ditambahkan dengan data valid.
     */
    public function test_tambah_pelanggan_berhasil(): void
    {
        $response = $this->post('/pelanggan', [
            'nama' => 'Ahmad Sanusi',
            'no_telepon' => '081234567890',
        ]);

        $response->assertRedirect(route('pelanggan.index'));
        $this->assertDatabaseHas('pelanggan', [
            'nama' => 'Ahmad Sanusi',
            'no_telepon' => '081234567890',
        ]);
    }

    /**
     * Validasi gagal jika form pelanggan tidak lengkap.
     */
    public function test_tambah_pelanggan_gagal_jika_nama_kosong(): void
    {
        $response = $this->post('/pelanggan', [
            'nama' => '',
            'no_telepon' => '081234567890',
        ]);

        $response->assertSessionHasErrors('nama');
        $this->assertDatabaseCount('pelanggan', 0);
    }

    /**
     * Data pelanggan dapat diubah.
     */
    public function test_ubah_pelanggan_berhasil(): void
    {
        $pelanggan = Pelanggan::create([
            'nama' => 'Nama Lama',
            'no_telepon' => '081200000000',
        ]);

        $response = $this->put("/pelanggan/{$pelanggan->id}", [
            'nama' => 'Nama Baru',
            'no_telepon' => '081299999999',
        ]);

        $response->assertRedirect(route('pelanggan.index'));
        $this->assertDatabaseHas('pelanggan', [
            'id' => $pelanggan->id,
            'nama' => 'Nama Baru',
            'no_telepon' => '081299999999',
        ]);
    }

    /**
     * Data pelanggan dapat dihapus.
     */
    public function test_hapus_pelanggan_berhasil(): void
    {
        $pelanggan = Pelanggan::create([
            'nama' => 'Pelanggan Hapus',
            'no_telepon' => '081233333333',
        ]);

        $response = $this->delete("/pelanggan/{$pelanggan->id}");

        $response->assertRedirect(route('pelanggan.index'));
        $this->assertDatabaseMissing('pelanggan', [
            'id' => $pelanggan->id,
        ]);
    }
}
