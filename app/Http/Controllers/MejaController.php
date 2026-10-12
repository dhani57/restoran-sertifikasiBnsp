<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Controller untuk mengelola data meja (KF-02).
 *
 * Menyediakan operasi CRUD: tambah, lihat, ubah, dan hapus meja.
 */
class MejaController extends Controller
{
    /**
     * Menampilkan daftar seluruh meja.
     */
    public function index(): View
    {
        $daftarMeja = Meja::orderBy('nomor_meja')->get();

        return view('meja.index', compact('daftarMeja'));
    }

    /**
     * Menampilkan form untuk menambah meja baru.
     */
    public function create(): View
    {
        return view('meja.create');
    }

    /**
     * Menyimpan data meja baru ke basis data.
     *
     * @param  Request  $request  Data dari form meja
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input dari form
        $data = $request->validate([
            'nomor_meja' => 'required|string|max:10|unique:meja,nomor_meja',
            'kapasitas' => 'required|integer|min:1',
        ]);

        try {
            // Simpan meja baru
            Meja::create($data);

            return redirect()->route('meja.index')
                ->with('success', 'Meja berhasil ditambahkan.');
        } catch (QueryException $e) {
            Log::error('Gagal menyimpan meja: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan pada basis data. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan form untuk mengubah data meja.
     *
     * @param  Meja  $meja  Instance meja dari route model binding
     */
    public function edit(Meja $meja): View
    {
        return view('meja.edit', compact('meja'));
    }

    /**
     * Memperbarui data meja di basis data.
     *
     * @param  Request  $request  Data dari form meja
     * @param  Meja  $meja  Instance meja dari route model binding
     */
    public function update(Request $request, Meja $meja): RedirectResponse
    {
        // Validasi input; nomor meja harus unik kecuali untuk meja yang sedang diubah
        $data = $request->validate([
            'nomor_meja' => 'required|string|max:10|unique:meja,nomor_meja,'.$meja->id,
            'kapasitas' => 'required|integer|min:1',
        ]);

        try {
            // Perbarui data meja
            $meja->update($data);

            return redirect()->route('meja.index')
                ->with('success', 'Meja berhasil diperbarui.');
        } catch (QueryException $e) {
            Log::error('Gagal memperbarui meja: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan pada basis data. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus data meja dari basis data.
     *
     * @param  Meja  $meja  Instance meja dari route model binding
     */
    public function destroy(Meja $meja): RedirectResponse
    {
        try {
            // Hapus meja beserta pesanan terkait (cascade)
            $meja->delete();

            return redirect()->route('meja.index')
                ->with('success', 'Meja berhasil dihapus.');
        } catch (QueryException $e) {
            Log::error('Gagal menghapus meja: '.$e->getMessage());

            return back()
                ->with('error', 'Terjadi kesalahan pada basis data. Silakan coba lagi.');
        }
    }
}
