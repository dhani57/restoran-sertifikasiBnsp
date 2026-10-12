<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Meja — mewakili tabel meja.
 *
 * Satu meja dapat dipesan berkali-kali pada tanggal atau jam yang berbeda
 * (relasi one-to-many ke pesanan).
 *
 * @property int $id
 * @property string $nomor_meja
 * @property int $kapasitas
 */
class Meja extends Model
{
    /** @var string Nama tabel di basis data. */
    protected $table = 'meja';

    /** @var array Kolom yang boleh diisi secara massal. */
    protected $fillable = ['nomor_meja', 'kapasitas'];

    /**
     * Relasi: satu meja memiliki banyak pesanan.
     */
    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class);
    }
}
