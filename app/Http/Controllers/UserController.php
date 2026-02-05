<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('pages.users.index', compact('users'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'username' => 'required|string|max:255|unique:users,username',
        'password' => 'required|string|min:6',
        'level' => 'required|in:admin,petugas,peminjam', // Update ini
    ]);

    $validated['password'] = Hash::make($validated['password']);

    User::create($validated);

    // Log aktivitas
    LogAktivitas::create([
        'user_id' => Auth::id(),
        'aktivitas' => 'Tambah User',
        'modul' => 'User',
        'timestamp' => now(),
    ]);

    return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
}

public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'username' => 'required|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
        'level' => 'required|in:admin,petugas,peminjam', // Update ini
    ]);

    if ($request->filled('password')) {
        $request->validate(['password' => 'string|min:6']);
        $validated['password'] = Hash::make($request->password);
    }

    $user->update($validated);

    // Log aktivitas
    LogAktivitas::create([
        'user_id' => Auth::id(),
        'aktivitas' => 'Update User',
        'modul' => 'User',
        'timestamp' => now(),
    ]);

    return redirect()->route('users.index')->with('success', 'User berhasil diupdate!');
}

    public function destroy(User $user)
    {
        // Tidak bisa hapus diri sendiri
        if ($user->user_id == Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus User',
            'modul' => 'User',
            'timestamp' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}