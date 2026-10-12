<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemesanan Restoran</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
        }
        .header p {
            margin: 0;
            color: #666;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th,
        table td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        table tbody tr:nth-child(even) {
            background-color: #fafafa;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            color: #999;
            text-align: right;
        }
        .status-dipesan { color: #0d6efd; font-weight: bold; }
        .status-selesai { color: #198754; font-weight: bold; }
        .status-dibatalkan { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pemesanan Restoran</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }} s.d. {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
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
                    <td>{{ $pesanan->meja->nomor_meja }}</td>
                    <td>{{ $pesanan->jumlah_orang }}</td>
                    <td>
                        @if ($pesanan->status === 'dipesan')
                            <span class="status-dipesan">Dipesan</span>
                        @elseif ($pesanan->status === 'selesai')
                            <span class="status-selesai">Selesai</span>
                        @else
                            <span class="status-dibatalkan">Dibatalkan</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data pesanan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p style="margin-top: 10px;"><strong>Total pesanan:</strong> {{ $daftarPesanan->count() }}</p>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
