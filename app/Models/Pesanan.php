<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Pesanan - mewakili tabel pesanan.
 *
 * Setiap pesanan terhubung ke satu pelanggan (belongsTo) dan satu meja (belongsTo).
 * Status pesanan dapat berupa: dipesan, selesai, atau dibatalkan.
 *
 * @property int $id
 * @property int $pelanggan_id
 * @property int $meja_id
 * @property string $tanggal
 * @property string $jam
 * @property int $jumlah_orang
 * @property string|null $catatan
 * @property string $status
 */
class Pesanan extends Model
{
    /** @var string Nama tabel di basis data. */
    protected $table = 'pesanan';

    /** @var array Kolom yang boleh diisi secara massal. */
    protected $fillable = [
        'pelanggan_id',
        'meja_id',
        'tanggal',
        'jam',
        'jumlah_orang',
        'catatan',
        'status',
    ];

    /**
     * Relasi: pesanan ini milik satu pelanggan.
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    /**
     * Relasi: pesanan ini menggunakan satu meja.
     */
    public function meja(): BelongsTo
    {
        return $this->belongsTo(Meja::class);
    }
}
