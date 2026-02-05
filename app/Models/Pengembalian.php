<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';
    protected $primaryKey = 'pengembalian_id'; // Tambahkan ini
    public $incrementing = true; // Tambahkan ini
    protected $keyType = 'int'; // Tambahkan ini

    protected $fillable = [
        'peminjaman_id',
        'tanggal_kembali_aktual',
        'kondisi_alat',
        'keterlambatan_hari',
        'tarif_denda_per_hari',
        'total_denda',
        'status_denda',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_kembali_aktual' => 'date',
        'tarif_denda_per_hari' => 'decimal:2',
        'total_denda' => 'decimal:2',
    ];

    // Relationships
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'peminjaman_id');
    }
    public function getRouteKeyName()
    {
    return 'pengembalian_id';
    }
}