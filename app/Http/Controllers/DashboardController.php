<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userLevel = strtolower(auth()->user()->level);
        
        // Data KPI
        $totalUsers = User::count();
        $totalAlat = Alat::sum('stok_total');
        $peminjamanPending = Peminjaman::where('status', 'menunggu')->count();
        $peminjamanAktif = Peminjaman::where('status', 'disetujui')->count();
        $totalPengembalian = Pengembalian::count();
        $totalDenda = Pengembalian::sum('total_denda');

        return view('pages.dashboard', compact(
            'totalUsers',
            'totalAlat',
            'peminjamanPending',
            'peminjamanAktif',
            'totalPengembalian',
            'totalDenda',
            'userLevel'
        ));
    }
}