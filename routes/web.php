<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Redirect root
Route::get('/', function () {
    // Cek session sudah login atau belum
    if (session('logged_in')) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Route login (GET - tampilkan form)
Route::get('/login', function () {
    // Kalau udah login, redirect ke dashboard
    if (session('logged_in')) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login');

// Route login (POST - proses login palsu)
Route::post('/login', function (Request $request) {
    $username = $request->input('username');
    $password = $request->input('password');
    
    // Hardcoded credentials (ganti sesuai kebutuhan)
    if ($username === 'admin' && $password === 'admin123') {
        // Set session logged in
        session(['logged_in' => true, 'username' => $username]);
        return redirect()->route('dashboard');
    }
    
    // Kalau salah, kembali ke login dengan error
    return back()->withErrors([
        'login' => 'Username atau password salah!',
    ])->onlyInput('username');
})->name('login.post');

// Route logout
Route::post('/logout', function (Request $request) {
    session()->forget('logged_in');
    session()->forget('username');
    return redirect()->route('login');
})->name('logout');

// Protected routes (cek session) - INI YANG DIPERBAIKI
Route::get('/dashboard', function () {
    // Cek apakah sudah login
    if (!session('logged_in')) {
        return redirect()->route('login');
    }
    return view('pages.dashboard');
})->name('dashboard');

// CRUD Alat Routes
Route::get('/alat', function () {
    if (!session('logged_in')) {
        return redirect()->route('login');
    }
    
    // Data dummy alat
    $alats = session('alats', []);
    
    return view('pages.alat.index', compact('alats'));
})->name('alat.index');

Route::post('/alat', function (Request $request) {
    $request->validate([
        'nama_alat' => 'required',
        'kategori' => 'required',
        'jumlah' => 'required|numeric',
        'kondisi' => 'required',
    ]);
    
    $alats = session('alats', []);
    $alats[] = [
        'id' => count($alats) + 1,
        'nama_alat' => $request->nama_alat,
        'kategori' => $request->kategori,
        'jumlah' => $request->jumlah,
        'tersedia' => $request->jumlah,
        'kondisi' => $request->kondisi,
    ];
    
    session(['alats' => $alats]);
    
    return redirect()->route('alat.index')->with('success', 'Alat berhasil ditambahkan!');
})->name('alat.store');

Route::delete('/alat/{id}', function ($id) {
    $alats = session('alats', []);
    $alats = array_filter($alats, function($alat) use ($id) {
        return $alat['id'] != $id;
    });
    
    session(['alats' => array_values($alats)]);
    
    return redirect()->route('alat.index')->with('success', 'Alat berhasil dihapus!');
})->name('alat.destroy');

// CRUD Peminjaman Routes
Route::get('/peminjaman', function () {
    if (!session('logged_in')) {
        return redirect()->route('login');
    }
    
    // Data dummy peminjaman (kosong dulu)
    $peminjaman = session('peminjaman', []);
    
    return view('pages.peminjaman.index', compact('peminjaman'));
})->name('peminjaman.index');

Route::post('/peminjaman', function (Request $request) {
    $request->validate([
        'alat' => 'required',
        'peminjam' => 'required',
        'jumlah' => 'required|numeric',
        'tgl_pinjam' => 'required|date',
        'jatuh_tempo' => 'required|date',
    ]);
    
    $peminjaman = session('peminjaman', []);
    $peminjaman[] = [
        'id' => count($peminjaman) + 1,
        'alat' => $request->alat,
        'peminjam' => $request->peminjam,
        'jumlah' => $request->jumlah,
        'tgl_pinjam' => $request->tgl_pinjam,
        'jatuh_tempo' => $request->jatuh_tempo,
        'status' => 'Dipinjam',
    ];
    
    session(['peminjaman' => $peminjaman]);
    
    return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil ditambahkan!');
})->name('peminjaman.store');

Route::delete('/peminjaman/{id}', function ($id) {
    $peminjaman = session('peminjaman', []);
    $peminjaman = array_filter($peminjaman, function($item) use ($id) {
        return $item['id'] != $id;
    });
    
    session(['peminjaman' => array_values($peminjaman)]);
    
    return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dihapus!');
})->name('peminjaman.destroy');

// CRUD Users Routes
Route::get('/users', function () {
    if (!session('logged_in')) {
        return redirect()->route('login');
    }
    
    // Data dummy users (kosong dulu)
    $users = session('users', []);
    
    return view('pages.users.index', compact('users'));
})->name('users.index');

Route::post('/users', function (Request $request) {
    $request->validate([
        'username' => 'required',
        'nama_lengkap' => 'required',
        'email' => 'required|email',
        'role' => 'required',
        'password' => 'required|min:6',
    ]);
    
    $users = session('users', []);
    $users[] = [
        'id' => count($users) + 1,
        'username' => $request->username,
        'nama_lengkap' => $request->nama_lengkap,
        'email' => $request->email,
        'role' => $request->role,
        'status' => 'Aktif',
    ];
    
    session(['users' => $users]);
    
    return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
})->name('users.store');

Route::delete('/users/{id}', function ($id) {
    $users = session('users', []);
    $users = array_filter($users, function($user) use ($id) {
        return $user['id'] != $id;
    });
    
    session(['users' => array_values($users)]);
    
    return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
})->name('users.destroy');

// CRUD Kategori Routes
Route::get('/kategori', function () {
    if (!session('logged_in')) {
        return redirect()->route('login');
    }
    
    // Data dummy kategori (kosong dulu)
    $kategori = session('kategori', []);
    
    return view('pages.kategori.index', compact('kategori'));
})->name('kategori.index');

Route::post('/kategori', function (Request $request) {
    $request->validate([
        'nama_kategori' => 'required',
        'deskripsi' => 'required',
    ]);
    
    $kategori = session('kategori', []);
    $kategori[] = [
        'id' => count($kategori) + 1,
        'nama_kategori' => $request->nama_kategori,
        'deskripsi' => $request->deskripsi,
    ];
    
    session(['kategori' => $kategori]);
    
    return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
})->name('kategori.store');

Route::delete('/kategori/{id}', function ($id) {
    $kategori = session('kategori', []);
    $kategori = array_filter($kategori, function($item) use ($id) {
        return $item['id'] != $id;
    });
    
    session(['kategori' => array_values($kategori)]);
    
    return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
})->name('kategori.destroy');

// CRUD Pengembalian Routes
Route::get('/pengembalian', function () {
    if (!session('logged_in')) {
        return redirect()->route('login');
    }
    
    // Data dummy pengembalian (kosong dulu)
    $pengembalian = session('pengembalian', []);
    
    return view('pages.pengembalian.index', compact('pengembalian'));
})->name('pengembalian.index');

Route::post('/pengembalian', function (Request $request) {
    $request->validate([
        'peminjam' => 'required',
        'alat' => 'required',
        'tgl_kembali' => 'required|date',
        'kondisi' => 'required',
        'terlambat' => 'required|numeric',
        'denda' => 'required|numeric',
    ]);
    
    $pengembalian = session('pengembalian', []);
    $pengembalian[] = [
        'id' => count($pengembalian) + 1,
        'peminjam' => $request->peminjam,
        'alat' => $request->alat,
        'tgl_kembali' => $request->tgl_kembali,
        'kondisi' => $request->kondisi,
        'terlambat' => $request->terlambat,
        'denda' => $request->denda,
    ];
    
    session(['pengembalian' => $pengembalian]);
    
    return redirect()->route('pengembalian.index')->with('success', 'Pengembalian berhasil diproses!');
})->name('pengembalian.store');

Route::delete('/pengembalian/{id}', function ($id) {
    $pengembalian = session('pengembalian', []);
    $pengembalian = array_filter($pengembalian, function($item) use ($id) {
        return $item['id'] != $id;
    });
    
    session(['pengembalian' => array_values($pengembalian)]);
    
    return redirect()->route('pengembalian.index')->with('success', 'Data pengembalian berhasil dihapus!');
})->name('pengembalian.destroy');

// Log Aktivitas Routes
Route::get('/log-aktivitas', function () {
    if (!session('logged_in')) {
        return redirect()->route('login');
    }
    
    // Data dummy log aktivitas (kosong dulu)
    $logs = session('logs', []);
    
    return view('pages.log.index', compact('logs'));
})->name('log.index');

// Laporan Routes
Route::get('/laporan', function () {
    if (!session('logged_in')) {
        return redirect()->route('login');
    }
    
    $peminjaman = session('peminjaman', []);
    $pengembalian = session('pengembalian', []);
    
    // Filter berdasarkan tanggal (default hari ini)
    $tanggal = request('tanggal', date('Y-m-d'));
    
    // Filter peminjaman hari ini
    $peminjamanHariIni = array_filter($peminjaman, function($p) use ($tanggal) {
        return $p['tgl_pinjam'] == $tanggal;
    });
    
    // Filter pengembalian hari ini
    $pengembalianHariIni = array_filter($pengembalian, function($p) use ($tanggal) {
        return $p['tgl_kembali'] == $tanggal;
    });
    
    $totalPeminjamanHariIni = count($peminjamanHariIni);
    $totalPengembalianHariIni = count($pengembalianHariIni);
    $totalDendaHariIni = array_sum(array_column($pengembalianHariIni, 'denda'));
    
    return view('pages.laporan.index', compact(
        'peminjaman', 'pengembalian', 'tanggal',
        'peminjamanHariIni', 'pengembalianHariIni',
        'totalPeminjamanHariIni', 'totalPengembalianHariIni', 'totalDendaHariIni'
    ));
})->name('laporan.index');

Route::get('/laporan/cetak', function () {
    if (!session('logged_in')) {
        return redirect()->route('login');
    }
    
    $peminjaman = session('peminjaman', []);
    $pengembalian = session('pengembalian', []);
    
    $tanggal = request('tanggal', date('Y-m-d'));
    
    $peminjamanHariIni = array_filter($peminjaman, function($p) use ($tanggal) {
        return $p['tgl_pinjam'] == $tanggal;
    });
    
    $pengembalianHariIni = array_filter($pengembalian, function($p) use ($tanggal) {
        return $p['tgl_kembali'] == $tanggal;
    });
    
    $totalPeminjamanHariIni = count($peminjamanHariIni);
    $totalPengembalianHariIni = count($pengembalianHariIni);
    $totalDendaHariIni = array_sum(array_column($pengembalianHariIni, 'denda'));
    
    return view('pages.laporan.cetak', compact(
        'tanggal', 'peminjamanHariIni', 'pengembalianHariIni',
        'totalPeminjamanHariIni', 'totalPengembalianHariIni', 'totalDendaHariIni'
    ));
})->name('laporan.cetak');