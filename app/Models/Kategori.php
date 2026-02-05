<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'kategori_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    // Specify route key name
    public function getRouteKeyName()
    {
        return 'kategori_id';
    }

    // Relationships
    public function alat()
    {
        return $this->hasMany(Alat::class, 'kategori_id', 'kategori_id');
    }
}