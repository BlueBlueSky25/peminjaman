@extends('layouts.app')

@section('title', 'Pengembalian Alat')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pengembalian Alat</h2>
            <p class="text-sm text-gray-600 mt-1">Kelola proses pengembalian alat yang dipinjam</p>
        </div>
        @if(auth()->user()->level == 'admin' || auth()->user()->level == 'petugas' || auth()->user()->level == 'peminjam')
            <button onclick="openModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition shadow-sm">
                <i class="fas fa-undo"></i>
                <span>Proses Pengembalian</span>
            </button>
        @endif
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

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Kembali</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terlambat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Denda</th>
                    @if(auth()->user()->level == 'admin')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pengembalian as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->peminjaman->user->username ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $item->peminjaman->alat->nama_alat ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $item->tanggal_kembali_aktual->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full font-semibold capitalize
                                @if($item->kondisi_alat == 'baik') bg-green-100 text-green-800
                                @elseif($item->kondisi_alat == 'rusak') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $item->kondisi_alat }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($item->keterlambatan_hari > 0)
                                <span class="text-red-600 font-medium">{{ $item->keterlambatan_hari }} hari</span>
                            @else
                                <span class="text-green-600">Tepat waktu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($item->total_denda > 0)
                                <span class="text-red-600">Rp {{ number_format($item->total_denda, 0, ',', '.') }}</span>
                            @else
                                <span class="text-gray-600">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full font-semibold
                                @if($item->status_denda == 'lunas') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ str_replace('_', ' ', ucfirst($item->status_denda)) }}
                            </span>
                        </td>
                        @if(auth()->user()->level == 'admin')
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('pengembalian.destroy', $item->pengembalian_id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data pengembalian ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                            <p>Belum ada data pengembalian.</p>
                            <p class="text-sm">Klik tombol "Proses Pengembalian" untuk menambahkan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Proses Pengembalian -->
    <div id="pengembalianModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Proses Pengembalian</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('pengembalian.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Peminjaman</label>
                    <select name="peminjaman_id" id="peminjaman_select" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Pilih Peminjaman</option>
                        @foreach(\App\Models\Peminjaman::with(['user', 'alat'])->where('status', 'disetujui')->whereDoesntHave('pengembalian')->get() as $pinjam)
                            <option value="{{ $pinjam->peminjaman_id }}" 
                                data-jatuh-tempo="{{ $pinjam->tanggal_kembali_rencana->format('Y-m-d') }}"
                                data-user="{{ $pinjam->user->username }}"
                                data-alat="{{ $pinjam->alat->nama_alat }}">
                                {{ $pinjam->user->username }} - {{ $pinjam->alat->nama_alat }} ({{ $pinjam->tanggal_peminjaman->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                    <p id="info_peminjaman" class="text-xs text-gray-500 mt-1"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kembali</label>
                    <input type="date" id="tanggal_kembali" name="tanggal_kembali_aktual" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        value="{{ date('Y-m-d') }}">
                    <p id="info_keterlambatan" class="text-xs mt-1"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Alat</label>
                    <select name="kondisi_alat" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Pilih Kondisi</option>
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                        <option value="hilang">Hilang</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Catatan tambahan (opsional)"></textarea>
                </div>

                <div class="flex space-x-2">
                    <button type="submit" 
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg transition">
                        Proses
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
            document.getElementById('pengembalianModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('pengembalianModal').classList.add('hidden');
        }

        // Hitung keterlambatan otomatis
        const peminjamanSelect = document.getElementById('peminjaman_select');
        const tanggalKembali = document.getElementById('tanggal_kembali');
        const infoPeminjaman = document.getElementById('info_peminjaman');
        const infoKeterlambatan = document.getElementById('info_keterlambatan');

        function hitungKeterlambatan() {
            const selected = peminjamanSelect.options[peminjamanSelect.selectedIndex];
            const jatuhTempo = selected.getAttribute('data-jatuh-tempo');
            const user = selected.getAttribute('data-user');
            const alat = selected.getAttribute('data-alat');
            
            if (jatuhTempo && tanggalKembali.value) {
                const tempo = new Date(jatuhTempo);
                const kembali = new Date(tanggalKembali.value);
                const diff = Math.ceil((kembali - tempo) / (1000 * 60 * 60 * 24));
                
                infoPeminjaman.textContent = `${user} - ${alat}`;
                
                if (diff > 0) {
                    const denda = diff * 50000;
                    infoKeterlambatan.innerHTML = `<span class="text-red-600 font-semibold">Terlambat ${diff} hari. Denda: Rp ${denda.toLocaleString('id-ID')}</span>`;
                } else {
                    infoKeterlambatan.innerHTML = `<span class="text-green-600">Tepat waktu</span>`;
                }
            } else {
                infoPeminjaman.textContent = '';
                infoKeterlambatan.textContent = '';
            }
        }

        peminjamanSelect.addEventListener('change', hitungKeterlambatan);
        tanggalKembali.addEventListener('change', hitungKeterlambatan);

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('pengembalianModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection