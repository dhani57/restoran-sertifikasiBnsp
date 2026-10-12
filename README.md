# Sistem Pemesanan Restoran

Aplikasi web sederhana untuk mencatat pemesanan meja restoran, menyimpan data pelanggan beserta pesanannya, dan menyajikan laporan pemesanan. Dibangun menggunakan framework Laravel dengan arsitektur MVC.

## Fitur Utama

- **Kelola Pelanggan** — Tambah, lihat, ubah, dan hapus data pelanggan.
- **Kelola Meja** — Tambah, lihat, ubah, dan hapus data meja restoran.
- **Kelola Pesanan** — Buat, lihat, ubah, hapus, dan ubah status pesanan meja dengan validasi ketersediaan otomatis.
- **Laporan Pemesanan** — Filter pesanan per rentang tanggal, lihat di layar, dan cetak ke PDF.

## Kebutuhan Sistem

- PHP >= 8.3
- Composer
- Laragon (MySQL)
- MySQL (basis data `restoran` dan `restoran_test` untuk pengujian)

## Langkah Instalasi

1. **Clone repositori** dan masuk ke direktori proyek:

   ```bash
   cd restoran-lsp
   ```

2. **Install dependensi PHP:**

   ```bash
   composer install
   ```

3. **Salin file environment:**

   ```bash
   copy .env.example .env
   ```

4. **Generate application key:**

   ```bash
   php artisan key:generate
   ```

5. **Buat basis data:**

   Buat basis data `restoran` dan `restoran_test` pada MySQL (Laragon).

6. **Sesuaikan konfigurasi database** pada file `.env`:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=restoran
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. **Jalankan migrasi dan seeder:**

   ```bash
   php artisan migrate --seed
   ```

## Menjalankan Aplikasi

```bash
php artisan serve
```

Buka browser dan akses `http://localhost:8000`.

## Menjalankan Pengujian

```bash
php artisan test
```

Pastikan basis data `restoran_test` sudah dibuat sebelum menjalankan pengujian.

## Struktur File Utama

| Lokasi | Isi |
| --- | --- |
| `database/migrations/` | Migration tabel `pelanggan`, `meja`, `pesanan` |
| `database/seeders/` | Seeder contoh data meja |
| `app/Models/` | `Pelanggan.php`, `Meja.php`, `Pesanan.php` |
| `app/Http/Controllers/` | `PelangganController`, `MejaController`, `PesananController`, `LaporanController` |
| `routes/web.php` | Route resource untuk pelanggan, meja, pesanan, serta route laporan |
| `resources/views/` | Halaman Blade: layout, pelanggan, meja, pesanan, laporan (termasuk `pdf.blade.php`) |
| `tests/Feature/` | `PesananTest.php`, `PelangganTest.php`, `MejaTest.php`, `LaporanTest.php` |

## Teknologi yang Digunakan

- **Laravel** — Framework PHP dengan arsitektur MVC
- **MySQL** — Basis data relasional
- **Bootstrap 5** — Framework CSS via CDN
- **barryvdh/laravel-dompdf** — Library untuk mencetak laporan ke PDF
- **PHPUnit** — Framework pengujian bawaan Laravel
