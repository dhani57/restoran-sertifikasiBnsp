<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller untuk laporan pemesanan (KF-05).
 *
 * Menyediakan fitur filter pesanan per rentang tanggal dan
 * pencetakan laporan ke format PDF menggunakan library barryvdh/laravel-dompdf
 * (lihat PRD Bagian 4.5).
 */
class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan dengan filter rentang tanggal.
     *
     * Jika parameter tanggal_awal dan tanggal_akhir diberikan,
     * data pesanan ditampilkan sesuai rentang tersebut.
     *
     * @param  Request  $request  Parameter filter tanggal
     */
    public function index(Request $request): View
    {
        $daftarPesanan = collect();
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        // Jika filter tanggal diberikan, ambil data pesanan
        if ($tanggalAwal && $tanggalAkhir) {
            $daftarPesanan = Pesanan::with(['pelanggan', 'meja'])
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->orderBy('tanggal')
                ->orderBy('jam')
                ->get();
        }

        return view('laporan.index', compact('daftarPesanan', 'tanggalAwal', 'tanggalAkhir'));
    }

    /**
     * Mencetak laporan pemesanan ke format PDF.
     *
     * Menggunakan library barryvdh/laravel-dompdf untuk menghasilkan
     * file PDF berisi tabel pesanan (tanggal, jam, pelanggan, meja,
     * jumlah orang, status).
     *
     * @param  Request  $request  Parameter filter tanggal
     * @return Response
     */
    public function cetak(Request $request)
    {
        // Validasi rentang tanggal
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        // Ambil data pesanan sesuai rentang tanggal
        $daftarPesanan = Pesanan::with(['pelanggan', 'meja'])
            ->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir])
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->get();

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        // Buat PDF dari view laporan
        $pdf = Pdf::loadView('laporan.pdf', compact('daftarPesanan', 'tanggalAwal', 'tanggalAkhir'));

        return $pdf->download('laporan-pemesanan.pdf');
    }
}
