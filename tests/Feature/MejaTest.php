<?php

namespace Tests\Feature;

use App\Models\Meja;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pengujian CRUD Meja (KF-02, PRD Bagian 2.2).
 */
class MejaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Halaman daftar meja dapat diakses.
     */
    public function test_halaman_meja_dapat_diakses(): void
    {
        $response = $this->get('/meja');

        $response->assertStatus(200);
        $response->assertSee('Daftar Meja');
    }

    /**
     * Meja baru dapat ditambahkan dengan data valid.
     */
    public function test_tambah_meja_berhasil(): void
    {
        $response = $this->post('/meja', [
            'nomor_meja' => 'M11',
            'kapasitas' => 6,
        ]);

        $response->assertRedirect(route('meja.index'));
        $this->assertDatabaseHas('meja', [
            'nomor_meja' => 'M11',
            'kapasitas' => 6,
        ]);
    }

    /**
     * Nomor meja harus unik.
     */
    public function test_tambah_meja_gagal_jika_nomor_meja_sudah_ada(): void
    {
        Meja::create(['nomor_meja' => 'M01', 'kapasitas' => 4]);

        $response = $this->post('/meja', [
            'nomor_meja' => 'M01',
            'kapasitas' => 2,
        ]);

        $response->assertSessionHasErrors('nomor_meja');
        $this->assertDatabaseCount('meja', 1);
    }

    /**
     * Data meja dapat diperbarui.
     */
    public function test_ubah_meja_berhasil(): void
    {
        $meja = Meja::create(['nomor_meja' => 'M01', 'kapasitas' => 4]);

        $response = $this->put("/meja/{$meja->id}", [
            'nomor_meja' => 'M01',
            'kapasitas' => 8,
        ]);

        $response->assertRedirect(route('meja.index'));
        $this->assertDatabaseHas('meja', [
            'id' => $meja->id,
            'kapasitas' => 8,
        ]);
    }

    /**
     * Data meja dapat dihapus.
     */
    public function test_hapus_meja_berhasil(): void
    {
        $meja = Meja::create(['nomor_meja' => 'M99', 'kapasitas' => 2]);

        $response = $this->delete("/meja/{$meja->id}");

        $response->assertRedirect(route('meja.index'));
        $this->assertDatabaseMissing('meja', [
            'id' => $meja->id,
        ]);
    }
}
