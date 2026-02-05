<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Alat;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['user', 'alat', 'petugas'])->latest()->get();
        return view('pages.peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $alats = Alat::where('stok_tersedia', '>', 0)->get();
        $users = User::where('level', 'Peminjam')->get();
        return view('pages.peminjaman.create', compact('alats', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'alat_id' => 'required|exists:alat,alat_id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_peminjaman',
            'tujuan_peminjaman' => 'nullable|string',
        ]);

        // Cek stok tersedia
        $alat = Alat::findOrFail($request->alat_id);
        if ($alat->stok_tersedia < $request->jumlah) {
            return back()->withErrors(['jumlah' => 'Stok alat tidak mencukupi!']);
        }

        DB::transaction(function () use ($validated, $alat) {
            // Buat peminjaman
            $validated['status'] = 'menunggu'; // Update
            Peminjaman::create($validated);

            // Kurangi stok tersedia
            $alat->decrement('stok_tersedia', $validated['jumlah']);
        });

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tambah Peminjaman',
            'modul' => 'Peminjaman',
            'timestamp' => now(),
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diajukan!');
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu,disetujui,ditolak,dikembalikan', // Update
        ]);

        // Jika disetujui, catat petugas dan waktu
        if ($request->status == 'disetujui' && $peminjaman->status != 'disetujui') {
            $validated['disetujui_oleh'] = Auth::id();
            $validated['tanggal_disetujui'] = now();
        }

        // Jika ditolak, kembalikan stok
        if ($request->status == 'ditolak' && $peminjaman->status != 'ditolak') {
            $peminjaman->alat->increment('stok_tersedia', $peminjaman->jumlah);
        }

        $peminjaman->update($validated);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Update Status Peminjaman',
            'modul' => 'Peminjaman',
            'timestamp' => now(),
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Status peminjaman berhasil diupdate!');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        // Kembalikan stok jika belum dikembalikan
        if ($peminjaman->status != 'dikembalikan') {
            $peminjaman->alat->increment('stok_tersedia', $peminjaman->jumlah);
        }

        $peminjaman->delete();

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus Peminjaman',
            'modul' => 'Peminjaman',
            'timestamp' => now(),
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dihapus!');
    }

    public function approve(Request $request, Peminjaman $peminjaman)
    {
        $peminjaman->update([
            'status' => 'disetujui',
            'disetujui_oleh' => Auth::id(),
            'tanggal_disetujui' => now(),
        ]);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menyetujui Peminjaman',
            'modul' => 'Peminjaman',
            'timestamp' => now(),
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil disetujui!');
    }
}