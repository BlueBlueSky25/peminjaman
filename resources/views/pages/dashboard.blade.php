@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h2>

    <!-- KPI Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Total Users - Admin Only -->
        @if($userLevel == 'admin')
            <x-kpi-card 
                title="Total Users" 
                value="{{ $totalUsers }}" 
                icon="fa-users" 
                color="blue"
            />
        @endif

        <!-- Total Alat - Admin & Petugas -->
        @if($userLevel == 'admin' || $userLevel == 'petugas')
            <x-kpi-card 
                title="Total Alat" 
                value="{{ $totalAlat }}" 
                icon="fa-wrench" 
                color="green"
            />
        @endif

        <!-- Peminjaman Pending - Semua Role -->
        <x-kpi-card 
            title="Peminjaman Pending" 
            value="{{ $peminjamanPending }}" 
            icon="fa-hourglass-half" 
            color="yellow"
        />

        <!-- Peminjaman Aktif - Semua Role -->
        <x-kpi-card 
            title="Peminjaman Aktif" 
            value="{{ $peminjamanAktif }}" 
            icon="fa-clipboard-check" 
            color="purple"
        />

        <!-- Total Pengembalian - Semua Role -->
        <x-kpi-card 
            title="Total Pengembalian" 
            value="{{ $totalPengembalian }}" 
            icon="fa-check-circle" 
            color="blue"
        />

        <!-- Total Denda - Semua Role -->
        <x-kpi-card 
            title="Total Denda" 
            value="Rp {{ number_format($totalDenda, 0, ',', '.') }}" 
            icon="fa-money-bill-wave" 
            color="red"
        />
    </div>

    <!-- Welcome Message -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-2">
            Selamat Datang! 👋
        </h3>
        <p class="text-gray-600">
            @if($userLevel == 'admin')
                Sistem Peminjaman Alat ini membantu Anda mengelola peminjaman alat dengan mudah. Gunakan menu di sidebar untuk mengakses berbagai fitur.
            @elseif($userLevel == 'petugas')
                Anda dapat menyetujui peminjaman, memantau pengembalian, dan mencetak laporan melalui menu di sidebar.
            @else
                Anda dapat melihat daftar alat yang tersedia dan mengajukan peminjaman melalui menu di sidebar.
            @endif
        </p>
    </div>
@endsection