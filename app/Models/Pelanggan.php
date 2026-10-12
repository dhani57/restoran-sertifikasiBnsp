<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Pelanggan — mewakili tabel pelanggan.
 *
 * Satu pelanggan dapat memiliki banyak pesanan (relasi one-to-many).
 * Properti $fillable membatasi kolom yang boleh diisi secara massal.
 *
 * @property int $id
 * @property string $nama
 * @property string $no_telepon
 */
class Pelanggan extends Model
{
    /** @var string Nama tabel di basis data. */
    protected $table = 'pelanggan';

    /** @var array Kolom yang boleh diisi secara massal. */
    protected $fillable = ['nama', 'no_telepon'];

    /**
     * Relasi: satu pelanggan memiliki banyak pesanan.
     */
    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class);
    }
}
