<header class="bg-white shadow-sm border-b-4 border-red-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <i class="fas fa-wrench text-gray-700 text-xl"></i>
            <h1 class="text-xl font-bold text-gray-800">Sistem Peminjaman Alat</h1>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- User Card -->
            <div class="flex items-center gap-3 bg-gray-50 px-4 py-2 rounded-lg border border-gray-200">
                <div class="flex items-center gap-2">
                    @php
                        $level = auth()->user()->level ?? 'admin';
                        $icon = match(strtolower($level)) {
                            'admin' => 'user-shield',
                            'petugas' => 'user-cog',
                            'peminjam' => 'user',
                            default => 'user',
                        };
                        $bgColor = match(strtolower($level)) {
                            'admin' => 'bg-red-500',
                            'petugas' => 'bg-blue-500',
                            'peminjam' => 'bg-green-500',
                            default => 'bg-gray-500',
                        };
                    @endphp
                    <div class="w-8 h-8 {{ $bgColor }} rounded-md flex items-center justify-center">
                        <i class="fas fa-{{ $icon }} text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800 leading-tight">
                            {{ auth()->user()->username ?? 'user' }}
                        </p>
                        <p class="text-xs text-gray-500 leading-tight capitalize">
                            {{ $level }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow-md">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</header>