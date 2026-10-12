@extends('layouts.app')

@section('title', 'Tambah Meja')

@section('content')
<div class="mb-3">
    <h1 class="h4"><i class="bi bi-plus-square"></i> Tambah Meja</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('meja.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nomor_meja" class="form-label">Nomor Meja <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nomor_meja') is-invalid @enderror"
                       id="nomor_meja" name="nomor_meja" value="{{ old('nomor_meja') }}"
                       required maxlength="10" placeholder="Contoh: M01">
                @error('nomor_meja')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="kapasitas" class="form-label">Kapasitas (Jumlah Kursi) <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('kapasitas') is-invalid @enderror"
                       id="kapasitas" name="kapasitas" value="{{ old('kapasitas') }}" required min="1">
                @error('kapasitas')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="{{ route('meja.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
