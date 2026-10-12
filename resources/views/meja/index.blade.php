@extends('layouts.app')

@section('title', 'Daftar Meja')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><i class="bi bi-grid-3x3"></i> Daftar Meja</h1>
    <a href="{{ route('meja.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Meja
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nomor Meja</th>
                    <th>Kapasitas (Kursi)</th>
                    <th style="width: 180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarMeja as $index => $meja)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="badge bg-secondary">{{ $meja->nomor_meja }}</span></td>
                        <td>{{ $meja->kapasitas }} orang</td>
                        <td>
                            <a href="{{ route('meja.edit', $meja) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Ubah
                            </a>
                            <form action="{{ route('meja.destroy', $meja) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus meja ini?')">
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
                            Belum ada data meja.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
