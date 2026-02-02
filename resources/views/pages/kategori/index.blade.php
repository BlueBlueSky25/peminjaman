@extends('layouts.app')

@section('title', 'Kategori Alat')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Kategori Alat</h2>
        <button onclick="openModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition">
            <i class="fas fa-plus"></i>
            <span>Tambah Kategori</span>
        </button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Card Grid -->
    @if(count($kategori) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($kategori as $item)
                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $item['nama_kategori'] }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ $item['deskripsi'] }}</p>
                    <div class="flex space-x-3 text-sm">
                        <button class="text-blue-600 hover:text-blue-900 font-medium">
                            Edit
                        </button>
                        <form action="{{ route('kategori.destroy', $item['id']) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg mb-2">Belum ada kategori</p>
            <p class="text-gray-400 text-sm">Klik tombol "Tambah Kategori" untuk menambahkan kategori baru.</p>
        </div>
    @endif

    <!-- Modal Tambah Kategori -->
    <div id="kategoriModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Tambah Kategori</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                    <input type="text" name="nama_kategori" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Contoh: Elektronik">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Deskripsi kategori"></textarea>
                </div>

                <div class="flex space-x-2">
                    <button type="submit" 
                        class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModal()" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 rounded-lg transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('kategoriModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('kategoriModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('kategoriModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection