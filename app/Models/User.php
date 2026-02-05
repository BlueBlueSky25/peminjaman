<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'username',
        'password',
        'level',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    // Specify route key name
    public function getRouteKeyName()
    {
        return 'user_id';
    }

    // Relationships
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'user_id', 'user_id');
    }

    public function peminjamanDiproses()
    {
        return $this->hasMany(Peminjaman::class, 'disetujui_oleh', 'user_id');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'user_id', 'user_id');
    }
}