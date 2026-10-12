<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Controller untuk mengelola data pelanggan (KF-01).
 *
 * Menyediakan operasi CRUD: tambah, lihat, ubah, dan hapus pelanggan.
 */
class PelangganController extends Controller
{
    /**
     * Menampilkan daftar seluruh pelanggan.
     */
    public function index(): View
    {
        $daftarPelanggan = Pelanggan::latest()->get();

        return view('pelanggan.index', compact('daftarPelanggan'));
    }

    /**
     * Menampilkan form untuk menambah pelanggan baru.
     */
    public function create(): View
    {
        return view('pelanggan.create');
    }

    /**
     * Menyimpan data pelanggan baru ke basis data.
     *
     * @param  Request  $request  Data dari form pelanggan
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input dari form
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:20',
        ]);

        try {
            // Simpan pelanggan baru
            Pelanggan::create($data);

            return redirect()->route('pelanggan.index')
                ->with('success', 'Pelanggan berhasil ditambahkan.');
        } catch (QueryException $e) {
            Log::error('Gagal menyimpan pelanggan: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan pada basis data. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan form untuk mengubah data pelanggan.
     *
     * @param  Pelanggan  $pelanggan  Instance pelanggan dari route model binding
     */
    public function edit(Pelanggan $pelanggan): View
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Memperbarui data pelanggan di basis data.
     *
     * @param  Request  $request  Data dari form pelanggan
     * @param  Pelanggan  $pelanggan  Instance pelanggan dari route model binding
     */
    public function update(Request $request, Pelanggan $pelanggan): RedirectResponse
    {
        // Validasi input dari form
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:20',
        ]);

        try {
            // Perbarui data pelanggan
            $pelanggan->update($data);

            return redirect()->route('pelanggan.index')
                ->with('success', 'Pelanggan berhasil diperbarui.');
        } catch (QueryException $e) {
            Log::error('Gagal memperbarui pelanggan: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan pada basis data. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus data pelanggan dari basis data.
     *
     * @param  Pelanggan  $pelanggan  Instance pelanggan dari route model binding
     */
    public function destroy(Pelanggan $pelanggan): RedirectResponse
    {
        try {
            // Hapus pelanggan beserta pesanan terkait (cascade)
            $pelanggan->delete();

            return redirect()->route('pelanggan.index')
                ->with('success', 'Pelanggan berhasil dihapus.');
        } catch (QueryException $e) {
            Log::error('Gagal menghapus pelanggan: '.$e->getMessage());

            return back()
                ->with('error', 'Terjadi kesalahan pada basis data. Silakan coba lagi.');
        }
    }
}
