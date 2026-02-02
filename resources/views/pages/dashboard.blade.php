@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h2>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Total Users -->
        <x-kpi-card 
            title="Total Users" 
            value="6" 
            icon="users" 
            color="blue" 
        />

        <!-- Total Alat -->
        <x-kpi-card 
            title="Total Alat" 
            value="10" 
            icon="wrench" 
            color="green" 
        />

        <!-- Peminjaman Pending -->
        <x-kpi-card 
            title="Peminjaman Pending" 
            value="0" 
            icon="hourglass-half" 
            color="yellow" 
        />

        <!-- Peminjaman Aktif -->
        <x-kpi-card 
            title="Peminjaman Aktif" 
            value="4" 
            icon="clipboard-check" 
            color="purple" 
        />

        <!-- Total Pengembalian -->
        <x-kpi-card 
            title="Total Pengembalian" 
            value="1" 
            icon="check-circle" 
            color="indigo" 
        />

        <!-- Total Denda -->
        <x-kpi-card 
            title="Total Denda" 
            value="Rp 0" 
            icon="money-bill-wave" 
            color="red" 
        />

    </div>

    <!-- Welcome Message -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
            Selamat Datang! 
            <i class="fas fa-hand-sparkles text-yellow-500 ml-2"></i>
        </h3>
        <p class="text-gray-600">
            Sistem Peminjaman Alat ini membantu Anda mengelola peminjaman alat dengan mudah. Gunakan menu di sidebar untuk mengakses berbagai fitur.
        </p>
    </div>
@endsection