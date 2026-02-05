<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        return view('pages.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        Kategori::create($validated);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tambah Kategori',
            'modul' => 'Kategori',
            'timestamp' => now(),
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $kategori->update($validated);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Update Kategori',
            'modul' => 'Kategori',
            'timestamp' => now(),
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus Kategori',
            'modul' => 'Kategori',
            'timestamp' => now(),
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}