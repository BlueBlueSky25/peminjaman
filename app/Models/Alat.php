<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alat';
    protected $primaryKey = 'alat_id'; // Tambahkan ini
    public $incrementing = true; // Tambahkan ini
    protected $keyType = 'int'; // Tambahkan ini

    protected $fillable = [
        'kategori_id',
        'nama_alat',
        'deskripsi',
        'kode_alat',
        'stok_total',
        'stok_tersedia',
        'kondisi',
        'lokasi',
    ];

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'kategori_id');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'alat_id', 'alat_id');
    }

    public function getRouteKeyName()
    {
    return 'alat_id';
    }
}