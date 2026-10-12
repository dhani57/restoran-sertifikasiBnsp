@extends('layouts.app')

@section('title', 'Laporan Pemesanan')

@section('content')
<div class="mb-3">
    <h1 class="h4"><i class="bi bi-file-earmark-bar-graph"></i> Laporan Pemesanan</h1>
</div>

{{-- Form Filter Tanggal --}}
<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('laporan.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal"
                       value="{{ $tanggalAwal }}" required>
            </div>
            <div class="col-md-4">
                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir"
                       value="{{ $tanggalAkhir }}" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-search"></i> Tampilkan
                </button>
                @if ($tanggalAwal && $tanggalAkhir)
                    <a href="{{ route('laporan.cetak', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir]) }}"
                       class="btn btn-success" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Tabel Hasil Laporan --}}
@if ($tanggalAwal && $tanggalAkhir)
    <div class="card">
        <div class="card-header bg-white">
            <strong>Laporan Pemesanan</strong>
            <span class="text-muted">
                ({{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }} —
                {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }})
            </span>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Pelanggan</th>
                        <th>Meja</th>
                        <th>Jumlah Orang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarPesanan as $index => $pesanan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($pesanan->jam)->format('H:i') }}</td>
                            <td>{{ $pesanan->pelanggan->nama }}</td>
                            <td><span class="badge bg-secondary">{{ $pesanan->meja->nomor_meja }}</span></td>
                            <td>{{ $pesanan->jumlah_orang }} orang</td>
                            <td>
                                @if ($pesanan->status === 'dipesan')
                                    <span class="badge badge-dipesan">Dipesan</span>
                                @elseif ($pesanan->status === 'selesai')
                                    <span class="badge badge-selesai">Selesai</span>
                                @else
                                    <span class="badge badge-dibatalkan">Dibatalkan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Tidak ada pesanan pada rentang tanggal tersebut.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($daftarPesanan->count() > 0)
            <div class="card-footer bg-white text-muted">
                Total: {{ $daftarPesanan->count() }} pesanan
            </div>
        @endif
    </div>
@endif
@endsection
