@extends('layouts.app')

@section('title', 'Daftar Pelanggan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><i class="bi bi-people"></i> Daftar Pelanggan</h1>
    <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Pelanggan
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama</th>
                    <th>No. Telepon</th>
                    <th style="width: 180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarPelanggan as $index => $pelanggan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pelanggan->nama }}</td>
                        <td>{{ $pelanggan->no_telepon }}</td>
                        <td>
                            <a href="{{ route('pelanggan.edit', $pelanggan) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Ubah
                            </a>
                            <form action="{{ route('pelanggan.destroy', $pelanggan) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
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
                        <td colspan="4" class="text-center text-muted py-4">
                            Belum ada data pelanggan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
