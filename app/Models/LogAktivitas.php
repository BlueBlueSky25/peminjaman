<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';
    protected $primaryKey = 'log_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'aktivitas',
        'modul',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Accessors untuk memudahkan di view
    public function getWaktuFormattedAttribute()
    {
        return $this->timestamp ? $this->timestamp->format('d-m-Y H:i:s') : '-';
    }
    
    public function getAksiAttribute($value)
    {
        return $value ?? '-';
    }
    
    public function getDeskripsiAttribute()
    {
        return $this->modul ?? '-';
    }
    
    public function getUserNamaAttribute()
    {
        return $this->user ? $this->user->nama_lengkap : 'User #' . $this->user_id;
    }
}