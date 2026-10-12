@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><i class="bi bi-journal-text"></i> Daftar Pesanan</h1>
    <a href="{{ route('pesanan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Buat Pesanan
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Pelanggan</th>
                    <th>Meja</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Jumlah Orang</th>
                    <th>Status</th>
                    <th style="width: 180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarPesanan as $index => $pesanan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pesanan->pelanggan->nama }}</td>
                        <td><span class="badge bg-secondary">{{ $pesanan->meja->nomor_meja }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($pesanan->jam)->format('H:i') }}</td>
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
                        <td>
                            <a href="{{ route('pesanan.edit', $pesanan) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Ubah
                            </a>
                            <form action="{{ route('pesanan.destroy', $pesanan) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            Belum ada pesanan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
