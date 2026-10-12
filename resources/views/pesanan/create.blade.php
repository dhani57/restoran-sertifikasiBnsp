@extends('layouts.app')

@section('title', 'Buat Pesanan')

@section('content')
<div class="mb-3">
    <h1 class="h4"><i class="bi bi-journal-plus"></i> Buat Pesanan Baru</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('pesanan.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="pelanggan_id" class="form-label">Pelanggan <span class="text-danger">*</span></label>
                <select class="form-select @error('pelanggan_id') is-invalid @enderror"
                        id="pelanggan_id" name="pelanggan_id" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach ($daftarPelanggan as $pelanggan)
                        <option value="{{ $pelanggan->id }}"
                            {{ old('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                            {{ $pelanggan->nama }} ({{ $pelanggan->no_telepon }})
                        </option>
                    @endforeach
                </select>
                @error('pelanggan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tanggal" class="form-label">Tanggal Kedatangan <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                           id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="jam" class="form-label">Jam Kedatangan <span class="text-danger">*</span></label>
                    <input type="time" class="form-control @error('jam') is-invalid @enderror"
                           id="jam" name="jam" value="{{ old('jam') }}" required>
                    @error('jam')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="jumlah_orang" class="form-label">Jumlah Orang <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('jumlah_orang') is-invalid @enderror"
                       id="jumlah_orang" name="jumlah_orang" value="{{ old('jumlah_orang') }}" required min="1">
                @error('jumlah_orang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Sistem akan otomatis mencarikan meja yang kapasitasnya mencukupi.</div>
            </div>

            <div class="mb-3">
                <label for="catatan" class="form-label">Catatan</label>
                <textarea class="form-control @error('catatan') is-invalid @enderror"
                          id="catatan" name="catatan" rows="3"
                          placeholder="Catatan pesanan makanan atau permintaan khusus (opsional)">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Pesanan
                </button>
                <a href="{{ route('pesanan.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
