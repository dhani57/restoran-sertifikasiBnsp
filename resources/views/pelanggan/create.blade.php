@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

@section('content')
<div class="mb-3">
    <h1 class="h4"><i class="bi bi-person-plus"></i> Tambah Pelanggan</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('pelanggan.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                       id="nama" name="nama" value="{{ old('nama') }}" required maxlength="100">
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="no_telepon" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                       id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" required maxlength="20">
                @error('no_telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
