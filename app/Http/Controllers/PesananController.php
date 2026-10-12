<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Controller untuk mengelola pesanan meja (KF-03 dan KF-04).
 *
 * Menyediakan operasi CRUD pesanan dengan validasi ketersediaan meja.
 * Logika pencarian meja tersedia menggunakan struktur perulangan dan
 * percabangan (lihat PRD Bagian 4.3).
 */
class PesananController extends Controller
{
    /**
     * Menampilkan daftar seluruh pesanan beserta data pelanggan dan meja.
     */
    public function index(): View
    {
        // Eager load relasi pelanggan dan meja untuk mengurangi query N+1
        $daftarPesanan = Pesanan::with(['pelanggan', 'meja'])->latest()->get();

        return view('pesanan.index', compact('daftarPesanan'));
    }

    /**
     * Menampilkan form untuk membuat pesanan baru.
     */
    public function create(): View
    {
        $daftarPelanggan = Pelanggan::orderBy('nama')->get();
        $daftarMeja = Meja::orderBy('nomor_meja')->get();

        return view('pesanan.create', compact('daftarPelanggan', 'daftarMeja'));
    }

    /**
     * Menyimpan pesanan meja baru setelah memvalidasi ketersediaan meja.
     *
     * Alur: validasi input → cari meja kosong → simpan pesanan.
     * Jika semua meja penuh, pesanan ditolak dengan pesan error.
     *
     * @param  Request  $request  Data dari form pesanan
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input dari form
        $data = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
            'jumlah_orang' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        try {
            // Cari meja kosong yang kapasitasnya mencukupi
            $meja = $this->cariMejaTersedia($data['tanggal'], $data['jam'], $data['jumlah_orang']);

            if ($meja === null) {
                // Semua meja penuh pada waktu tersebut
                return back()->withInput()
                    ->with('error', 'Maaf, semua meja penuh pada waktu tersebut.');
            }

            // Simpan pesanan dengan meja yang ditemukan
            Pesanan::create([
                'pelanggan_id' => $data['pelanggan_id'],
                'meja_id' => $meja->id,
                'tanggal' => $data['tanggal'],
                'jam' => $data['jam'],
                'jumlah_orang' => $data['jumlah_orang'],
                'catatan' => $data['catatan'] ?? null,
                'status' => 'dipesan',
            ]);

            return redirect()->route('pesanan.index')
                ->with('success', 'Pesanan berhasil disimpan.');
        } catch (QueryException $e) {
            Log::error('Gagal menyimpan pesanan: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan pada basis data. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan form untuk mengubah data pesanan.
     *
     * @param  Pesanan  $pesanan  Instance pesanan dari route model binding
     */
    public function edit(Pesanan $pesanan): View
    {
        $daftarPelanggan = Pelanggan::orderBy('nama')->get();
        $daftarMeja = Meja::orderBy('nomor_meja')->get();

        return view('pesanan.edit', compact('pesanan', 'daftarPelanggan', 'daftarMeja'));
    }

    /**
     * Memperbarui data pesanan di basis data.
     *
     * Melakukan validasi ulang ketersediaan meja jika tanggal atau jam berubah.
     *
     * @param  Request  $request  Data dari form pesanan
     * @param  Pesanan  $pesanan  Instance pesanan dari route model binding
     */
    public function update(Request $request, Pesanan $pesanan): RedirectResponse
    {
        // Validasi input dari form
        $data = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
            'jumlah_orang' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
            'status' => 'required|in:dipesan,selesai,dibatalkan',
        ]);

        try {
            // Periksa apakah tanggal atau jam berubah, jika ya validasi ulang meja
            $tanggalAtauJamBerubah = $pesanan->tanggal !== $data['tanggal']
                || $pesanan->jam !== $data['jam']
                || $pesanan->jumlah_orang !== (int) $data['jumlah_orang'];

            if ($tanggalAtauJamBerubah && $data['status'] === 'dipesan') {
                // Cari meja yang tersedia (kecuali meja yang sudah dipakai pesanan ini)
                $meja = $this->cariMejaTersedia(
                    $data['tanggal'],
                    $data['jam'],
                    $data['jumlah_orang'],
                    $pesanan->id
                );

                if ($meja === null) {
                    return back()->withInput()
                        ->with('error', 'Maaf, semua meja penuh pada waktu tersebut.');
                }

                $data['meja_id'] = $meja->id;
            }

            // Perbarui data pesanan
            $pesanan->update($data);

            return redirect()->route('pesanan.index')
                ->with('success', 'Pesanan berhasil diperbarui.');
        } catch (QueryException $e) {
            Log::error('Gagal memperbarui pesanan: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan pada basis data. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus pesanan dari basis data.
     *
     * @param  Pesanan  $pesanan  Instance pesanan dari route model binding
     */
    public function destroy(Pesanan $pesanan): RedirectResponse
    {
        try {
            // Hapus pesanan
            $pesanan->delete();

            return redirect()->route('pesanan.index')
                ->with('success', 'Pesanan berhasil dihapus.');
        } catch (QueryException $e) {
            Log::error('Gagal menghapus pesanan: '.$e->getMessage());

            return back()
                ->with('error', 'Terjadi kesalahan pada basis data. Silakan coba lagi.');
        }
    }

    /**
     * Mencari meja tersedia berdasarkan tanggal, jam, dan jumlah orang.
     *
     * Menggunakan struktur perulangan (foreach) dan percabangan (if-else)
     * untuk memeriksa ketersediaan meja satu per satu (PRD Bagian 4.3).
     *
     * Meja dinyatakan penuh apabila sudah ada pesanan berstatus 'dipesan'
     * pada tanggal dan jam yang sama (PRD Bagian 3.2).
     *
     * @param  string  $tanggal  Tanggal kedatangan (format: Y-m-d)
     * @param  string  $jam  Jam kedatangan (format: H:i)
     * @param  int  $jumlahOrang  Jumlah tamu
     * @param  int|null  $kecualiPesananId  ID pesanan yang dikecualikan (untuk update)
     * @return Meja|null Meja yang tersedia, atau null jika semua penuh
     */
    private function cariMejaTersedia(string $tanggal, string $jam, int $jumlahOrang, ?int $kecualiPesananId = null): ?Meja
    {
        // Ambil semua meja yang kapasitasnya mencukupi jumlah orang
        $daftarMeja = Meja::where('kapasitas', '>=', $jumlahOrang)->get();

        foreach ($daftarMeja as $meja) {
            // Periksa apakah meja ini sudah dipesan pada tanggal dan jam yang sama
            $query = Pesanan::where('meja_id', $meja->id)
                ->where('tanggal', $tanggal)
                ->where('jam', $jam)
                ->where('status', 'dipesan');

            // Kecualikan pesanan yang sedang diubah (untuk fitur update)
            if ($kecualiPesananId !== null) {
                $query->where('id', '!=', $kecualiPesananId);
            }

            $sudahDipesan = $query->exists();

            if (! $sudahDipesan) {
                return $meja; // meja kosong ditemukan
            }
        }

        return null; // semua meja penuh
    }
}
