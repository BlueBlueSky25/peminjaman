<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'level' => 'admin', // lowercase
        ]);

        // Create Petugas User
        User::create([
            'username' => 'petugas',
            'password' => Hash::make('petugas123'),
            'level' => 'petugas', // lowercase
        ]);

        // Create Peminjam User
        User::create([
            'username' => 'peminjam',
            'password' => Hash::make('peminjam123'),
            'level' => 'peminjam', // lowercase
        ]);

        // Create Kategori
        Kategori::create([
            'nama_kategori' => 'Elektronik',
            'deskripsi' => 'Alat-alat elektronik seperti laptop, proyektor, dll',
        ]);

        Kategori::create([
            'nama_kategori' => 'Mekanik',
            'deskripsi' => 'Alat-alat mekanik dan perkakas',
        ]);
    }
}