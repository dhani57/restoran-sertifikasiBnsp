# Product Requirements Document (PRD)

## Sistem Pemesanan Restoran

Lampiran Dokumen Laporan Pengembangan Program · Skema Okupasi Programmer (Pemrogram) · LSP Universitas Lampung

Disusun oleh: \[Nama Peserta\] · Oktober 2026

## 1. Deskripsi Singkat Proyek

### 1.1 Latar Belakang dan Tujuan

Restoran memerlukan aplikasi sederhana untuk mencatat pemesanan meja, menyimpan data pelanggan beserta pesanannya, dan menyajikan laporan pemesanan. Sistem Pemesanan Restoran dibangun sebagai aplikasi web yang dioperasikan oleh satu pengguna, yaitu Admin, tanpa fitur login, sehingga alur kerja mudah dipahami, dikembangkan, dan dijelaskan.

### 1.2 Ruang Lingkup

Yang termasuk dalam sistem:

- Pengelolaan data pelanggan, meja, dan pesanan (tambah, lihat, ubah, hapus).
- Validasi ketersediaan meja saat pesanan dibuat.
- Laporan pemesanan per rentang tanggal, dapat dilihat di layar dan dicetak ke PDF.

Yang tidak termasuk (sengaja dibatasi agar tetap sederhana):

- Fitur login, banyak role pengguna, registrasi pelanggan mandiri, dan pembayaran online.
- Manajemen menu makanan dan harga, stok, serta notifikasi.
- API, antarmuka seluler khusus, dan fitur real-time.

### 1.3 Spesifikasi Teknologi

| Komponen | Teknologi |
| --- | --- |
| Framework | Laravel (arsitektur MVC) |
| Basis data | MySQL (dijalankan melalui Laragon) |
| Tampilan | Blade template dengan Bootstrap atau Tailwind via CDN |
| Cetak laporan | Library barryvdh/laravel-dompdf |
| Pengujian | PHPUnit (bawaan Laravel, dijalankan dengan `php artisan test`) |

## 2. Kebutuhan Fungsional

### 2.1 Pengguna Sistem

Sistem hanya memiliki **satu role, yaitu Admin**. Sistem tidak memiliki fitur login: aplikasi langsung terbuka ke halaman utama, dan Admin dapat mengelola seluruh data. Tidak ada pembagian hak akses.

### 2.2 Daftar Kebutuhan Fungsional

| Kode | Kebutuhan | Deskripsi Singkat |
| --- | --- | --- |
| KF-01 | Kelola Pelanggan | Admin menambah, melihat, mengubah, dan menghapus data pelanggan (nama, nomor telepon). |
| KF-02 | Kelola Meja | Admin menambah, melihat, mengubah, dan menghapus data meja (nomor meja, kapasitas). |
| KF-03 | Kelola Pesanan | Admin membuat, melihat, mengubah, dan menghapus pesanan meja. Sistem memvalidasi ketersediaan meja sebelum pesanan disimpan. |
| KF-04 | Ubah Status Pesanan | Admin mengubah status pesanan menjadi *dipesan*, *selesai*, atau *dibatalkan*. |
| KF-05 | Laporan Pemesanan | Admin memilih rentang tanggal, melihat daftar pesanan, lalu mencetaknya ke PDF. |

### 2.3 Kebutuhan Non-Fungsional (Ringkas)

- Antarmuka sederhana dan berbahasa Indonesia.
- Seluruh input divalidasi dan kesalahan ditampilkan sebagai pesan yang jelas.
- Kode mengikuti konvensi penamaan Laravel dan diberi komentar.

### 2.4 Use Case Sederhana

Aktor tunggal: **Admin**.

| Use Case | Aktor | Keterangan |
| --- | --- | --- |
| Kelola Pelanggan | Admin | Create, read, update, delete pelanggan. |
| Kelola Meja | Admin | Create, read, update, delete meja. |
| Kelola Pesanan | Admin | Membuat pesanan dengan validasi meja, serta ubah, hapus, dan ubah status. |
| Lihat dan Cetak Laporan | Admin | Filter tanggal, tampil di layar, unduh PDF. |

Alur utama membuat pesanan: Admin mengisi form (pelanggan, tanggal, jam, jumlah orang) → sistem memvalidasi input → sistem mencari meja kosong → jika ada, pesanan disimpan; jika tidak, sistem menampilkan pesan "meja penuh".

## 3. Struktur Database

Basis data bernama `restoran` terdiri dari tiga tabel buatan sendiri: `pelanggan`, `meja`, dan `pesanan`. Karena tidak ada login, tidak ada tabel pengguna; tabel bawaan Laravel (misalnya `users` dan `sessions`) dibiarkan apa adanya dan tidak diubah.

### 3.1 Daftar Tabel

**Tabel `pelanggan`**

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| id | BIGINT, PK |  |
| nama | VARCHAR(100) | Wajib |
| no\_telepon | VARCHAR(20) | Wajib |
| created\_at, updated\_at | TIMESTAMP |  |

**Tabel `meja`**

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| id | BIGINT, PK |  |
| nomor\_meja | VARCHAR(10), unik | Contoh: M01 |
| kapasitas | INT | Jumlah kursi |
| created\_at, updated\_at | TIMESTAMP |  |

**Tabel `pesanan`**

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| id | BIGINT, PK |  |
| pelanggan\_id | BIGINT, FK | Mengacu ke `pelanggan.id` |
| meja\_id | BIGINT, FK | Mengacu ke `meja.id` |
| tanggal | DATE | Tanggal kedatangan |
| jam | TIME | Jam kedatangan |
| jumlah\_orang | INT |  |
| catatan | TEXT, nullable | Catatan pesanan makanan atau permintaan khusus |
| status | VARCHAR(20) | `dipesan` (bawaan), `selesai`, `dibatalkan` |
| created\_at, updated\_at | TIMESTAMP |  |

### 3.2 Relasi (ERD Sederhana)

```
pelanggan (1) ──────< (N) pesanan (N) >────── (1) meja
```

- Satu pelanggan dapat memiliki banyak pesanan.
- Satu meja dapat dipesan berkali-kali pada tanggal atau jam yang berbeda.
- Meja dinyatakan **penuh** apabila sudah ada pesanan berstatus `dipesan` pada tanggal dan jam yang sama.

Catatan: untuk lampiran, gambar ERD dan Use Case dapat dibuat dengan draw.io atau dbdiagram.io berdasarkan tabel di atas.

## 4. Pemetaan Pemenuhan 9 Unit Kompetensi

### 4.1 J.620100.009.01 — Menggunakan Spesifikasi Program

**Pemenuhan:** Pengembangan mengacu pada spesifikasi tertulis berupa dokumen PRD ini.

**Diagram acuan:**

- ERD (Bagian 3) sebagai dasar pembuatan migration dan model.
- Use Case (Bagian 2.4) sebagai dasar pembuatan route, controller, dan halaman.
- Alur pembuatan pesanan (Bagian 2.4) sebagai dasar logika validasi.

**Bukti:** dokumen PRD, gambar ERD, gambar Use Case, dan file migration di `database/migrations/`.

### 4.2 J.620100.016.01 — Menerapkan Best Practices Pemrograman

**Pemenuhan:** seluruh kode mengikuti aturan penamaan dan kerapian berikut.

| Elemen | Aturan | Contoh |
| --- | --- | --- |
| Class dan Model | PascalCase, tunggal | `Pelanggan`, `PesananController` |
| Variabel dan method | camelCase | `$jumlahOrang`, `cariMejaTersedia()` |
| Kolom dan tabel basis data | snake\_case | `no_telepon`, `pesanan` |
| Route dan nama view | huruf kecil, deskriptif | `pesanan.index`, `laporan.cetak` |

Aturan kerapian: indentasi 4 spasi, satu class per file, method pendek dengan satu tugas, tidak ada kode yang dikomentari tanpa alasan, dan tidak ada variabel yang tidak dipakai. Validasi input dilakukan di controller dengan `$request->validate()`.

**Bukti:** seluruh file di `app/`, `routes/web.php`, dan `resources/views/`.

### 4.3 J.620100.017.02 — Menulis Kode dengan Prinsip Pemrograman Terstruktur

**Pemenuhan:** logika validasi ketersediaan meja memakai struktur perulangan (`foreach`) dan percabangan (`if-else`). Sistem mengambil semua meja yang kapasitasnya mencukupi, lalu memeriksa satu per satu apakah meja tersebut sudah dipesan pada tanggal dan jam yang sama.

```php
private function cariMejaTersedia(string $tanggal, string $jam, int $jumlahOrang): ?Meja
{
    $daftarMeja = Meja::where('kapasitas', '>=', $jumlahOrang)->get();

    foreach ($daftarMeja as $meja) {
        $sudahDipesan = Pesanan::where('meja_id', $meja->id)
            ->where('tanggal', $tanggal)
            ->where('jam', $jam)
            ->where('status', 'dipesan')
            ->exists();

        if (!$sudahDipesan) {
            return $meja; // meja kosong ditemukan
        }
    }

    return null; // semua meja penuh
}
```

Pada `store()`, hasil fungsi diperiksa dengan `if ($meja === null) { ... } else { ... }`: jika `null`, pesanan ditolak dengan pesan "meja penuh"; jika tidak, pesanan disimpan.

**Bukti:** method `cariMejaTersedia()` dan `store()` di `PesananController`.

### 4.4 J.620100.018.02 — Menerapkan Pemrograman Berorientasi Objek

**Pemenuhan:** Laravel menggunakan class dan pewarisan (inheritance) secara langsung.

| Class | Mewarisi dari | Peran |
| --- | --- | --- |
| `Pelanggan`, `Meja`, `Pesanan` | `Illuminate\Database\Eloquent\Model` | Model yang mewakili tabel |
| `PelangganController`, `MejaController`, `PesananController`, `LaporanController` | `App\Http\Controllers\Controller` | Mengatur alur permintaan dan respons |
| `PesananTest` | `Tests\TestCase` | Kelas pengujian |

Prinsip OOP yang dapat dijelaskan ke asesor:

- **Class dan objek:** `Pesanan::create([...])` membuat objek `Pesanan`.
- **Inheritance:** model dan controller mewarisi method dari class induk Laravel.
- **Enkapsulasi:** properti `$fillable` membatasi kolom yang boleh diisi, dan method `cariMejaTersedia()` bersifat `private`.
- **Relasi antarobjek:** `Pesanan` memiliki `belongsTo(Pelanggan::class)` dan `belongsTo(Meja::class)`.

```php
class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $fillable = ['pelanggan_id', 'meja_id', 'tanggal', 'jam',
                           'jumlah_orang', 'catatan', 'status'];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function meja()
    {
        return $this->belongsTo(Meja::class);
    }
}
```

**Bukti:** folder `app/Models/` dan `app/Http/Controllers/`.

### 4.5 J.620100.019.02 — Menggunakan Library atau Komponen Pre-Existing

**Pemenuhan:** pencetakan laporan menggunakan library `barryvdh/laravel-dompdf`, dipasang melalui Composer.

```
composer require barryvdh/laravel-dompdf
```

```php
use Barryvdh\DomPDF\Facade\Pdf;

public function cetak(Request $request)
{
    $daftarPesanan = Pesanan::with(['pelanggan', 'meja'])
        ->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir])
        ->get();

    $pdf = Pdf::loadView('laporan.pdf', compact('daftarPesanan'));

    return $pdf->download('laporan-pemesanan.pdf');
}
```

View `laporan/pdf.blade.php` berisi tabel pesanan (tanggal, jam, pelanggan, meja, jumlah orang, status). Laravel sendiri, Bootstrap/Tailwind CDN, dan PHPUnit juga termasuk komponen pre-existing yang digunakan.

**Bukti:** `composer.json`, `LaporanController`, dan `resources/views/laporan/pdf.blade.php`.

### 4.6 J.620100.021.02 — Mengimplementasikan Pemrograman Akses Basis Data

**Pemenuhan:** seluruh akses data memakai Eloquent ORM, tanpa query SQL manual. Koneksi MySQL diatur di file `.env` (`DB_CONNECTION=mysql`, `DB_DATABASE=restoran`). Struktur tabel dibuat dengan migration.

| Operasi | Pelanggan | Pesanan |
| --- | --- | --- |
| Create | `Pelanggan::create($data)` | `Pesanan::create($data)` |
| Read | `Pelanggan::all()` | `Pesanan::with(['pelanggan', 'meja'])->get()` |
| Update | `$pelanggan->update($data)` | `$pesanan->update($data)` |
| Delete | `$pelanggan->delete()` | `$pesanan->delete()` |

Selain itu digunakan relasi Eloquent (`with()`, `belongsTo`, `hasMany`) dan filter `whereBetween()` untuk laporan.

**Bukti:** file migration, model, dan controller; isi tabel pada MySQL (Laragon).

### 4.7 J.620100.023.02 — Mendokumentasikan Kode Program

**Pemenuhan:** setiap class dan method diberi komentar DocBlock dengan format berikut.

```php
/**
 * Menyimpan pesanan meja baru setelah memvalidasi ketersediaan meja.
 *
 * @param  \Illuminate\Http\Request  $request  Data dari form pesanan
 * @return \Illuminate\Http\RedirectResponse
 */
public function store(Request $request)
```

Komentar satu baris (`//`) dipakai pada langkah logika yang penting, misalnya "cari meja kosong" dan "simpan pesanan".

File `README.md` di root proyek berisi:

1. Judul dan deskripsi singkat sistem.
2. Kebutuhan sistem (PHP, Composer, Laragon, MySQL).
3. Langkah instalasi: `composer install`, salin `.env.example` menjadi `.env`, `php artisan key:generate`, buat basis data `restoran`.
4. Perintah migrasi dan seeder: `php artisan migrate --seed`.
5. Cara menjalankan aplikasi (`php artisan serve`).
6. Cara menjalankan pengujian (`php artisan test`).

**Bukti:** komentar pada file controller dan model, serta file `README.md`.

### 4.8 J.620100.025.02 — Melakukan Debugging

**Pemenuhan:** operasi yang berisiko gagal dibungkus `try-catch`. Kesalahan query ditangkap, dicatat ke log, dan ditampilkan sebagai pesan yang ramah kepada Admin, sehingga aplikasi tidak menampilkan halaman error mentah.

```php
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

try {
    $meja = $this->cariMejaTersedia($data['tanggal'], $data['jam'], $data['jumlah_orang']);

    if ($meja === null) {
        return back()->withInput()->with('error', 'Maaf, semua meja penuh pada waktu tersebut.');
    }

    Pesanan::create([...$data, 'meja_id' => $meja->id]);

    return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil disimpan.');
} catch (QueryException $e) {
    Log::error('Gagal menyimpan pesanan: ' . $e->getMessage());

    return back()->withInput()->with('error', 'Terjadi kesalahan pada basis data. Silakan coba lagi.');
}
```

Teknik debugging yang dapat didemonstrasikan: membaca `storage/logs/laravel.log`, memakai `dd()` atau `Log::info()` untuk menelusuri nilai variabel, dan menjalankan `php artisan test` untuk mendeteksi kesalahan logika.

**Bukti:** blok `try-catch` pada controller, file log, dan tampilan pesan error di halaman.

### 4.9 J.620100.033.02 — Melaksanakan Pengujian Unit Program

**Pemenuhan:** pengujian memakai PHPUnit bawaan Laravel. Basis data pengujian terpisah (`restoran_test`) diatur pada `phpunit.xml`, dan trait `RefreshDatabase` mengosongkan data setiap pengujian.

| Kode | Skenario | Hasil yang Diharapkan |
| --- | --- | --- |
| UT-01 | Admin membuat pesanan dengan data valid dan meja tersedia | Pesanan tersimpan di basis data dan sistem diarahkan ke daftar pesanan |
| UT-02 | Admin membuat pesanan pada tanggal dan jam yang meja-nya sudah penuh | Pesanan tidak tersimpan dan muncul pesan "meja penuh" |
| UT-03 | Admin membuat pesanan dengan data tidak lengkap (misalnya tanpa tanggal) | Muncul kesalahan validasi dan pesanan tidak tersimpan |

Contoh pengujian UT-02:

```php
class PesananTest extends TestCase
{
    use RefreshDatabase;

    /** Pesanan harus gagal jika semua meja sudah dipesan. */
    public function test_pesanan_gagal_jika_meja_penuh(): void
    {
        $pelanggan = Pelanggan::create(['nama' => 'Budi', 'no_telepon' => '08123456789']);
        $meja = Meja::create(['nomor_meja' => 'M01', 'kapasitas' => 4]);

        Pesanan::create([
            'pelanggan_id' => $pelanggan->id, 'meja_id' => $meja->id,
            'tanggal' => '2026-11-01', 'jam' => '19:00',
            'jumlah_orang' => 2, 'status' => 'dipesan',
        ]);

        $response = $this->post('/pesanan', [
            'pelanggan_id' => $pelanggan->id,
            'tanggal' => '2026-11-01', 'jam' => '19:00', 'jumlah_orang' => 2,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('pesanan', 1);
    }
}
```

Pengujian dijalankan dengan `php artisan test`, dan hasil (seluruh pengujian lulus) dijadikan bukti berupa tangkapan layar.

**Bukti:** file `tests/Feature/PesananTest.php` dan tangkapan layar hasil pengujian.

## 5. Ringkasan Struktur File Utama

| Lokasi | Isi |
| --- | --- |
| `database/migrations/` | Migration tabel `pelanggan`, `meja`, `pesanan` |
| `database/seeders/` | Seeder contoh data meja |
| `app/Models/` | `Pelanggan.php`, `Meja.php`, `Pesanan.php` |
| `app/Http/Controllers/` | `PelangganController`, `MejaController`, `PesananController`, `LaporanController` |
| `routes/web.php` | Route resource untuk pelanggan, meja, pesanan, serta route laporan |
| `resources/views/` | Halaman Blade: layout, pelanggan, meja, pesanan, laporan (termasuk `pdf.blade.php`) |
| `tests/Feature/` | `PesananTest.php` |
| `README.md` | Dokumentasi instalasi dan penggunaan |
