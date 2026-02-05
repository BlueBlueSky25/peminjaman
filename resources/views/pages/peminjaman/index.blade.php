@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Peminjaman</h2>
        @if(auth()->user()->level == 'admin' || auth()->user()->level == 'peminjam')
            <button onclick="openModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition">
                <i class="fas fa-plus"></i>
                <span>Ajukan Peminjaman</span>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($peminjaman as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->alat->nama_alat ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->user->username ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->jumlah }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->tanggal_peminjaman->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full capitalize
                                @if($item->status == 'disetujui') bg-green-100 text-green-800
                                @elseif($item->status == 'dikembalikan') bg-blue-100 text-blue-800
                                @elseif($item->status == 'ditolak') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            @if(auth()->user()->level == 'admin' && $item->status == 'menunggu')
                                <button onclick="approveModal({{ $item->peminjaman_id }})" class="text-green-600 hover:text-green-900" title="Setujui">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button onclick="rejectModal({{ $item->peminjaman_id }})" class="text-red-600 hover:text-red-900" title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                            @if(auth()->user()->level == 'admin')
                                <form action="{{ route('peminjaman.destroy', $item->peminjaman_id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus peminjaman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                            <p>Belum ada data peminjaman.</p>
                            <p class="text-sm">Klik tombol "Ajukan Peminjaman" untuk menambahkan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Ajukan Peminjaman -->
    <div id="peminjamanModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Ajukan Peminjaman</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('peminjaman.store') }}" method="POST">
                @csrf
                
                <!-- User ID (Hidden - Auto from logged in user or select for admin) -->
                @if(auth()->user()->level == 'admin')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Peminjam</label>
                        <select name="user_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Peminjam</option>
                            @foreach(\App\Models\User::where('level', 'peminjam')->get() as $user)
                                <option value="{{ $user->user_id }}">{{ $user->username }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                @endif

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alat</label>
                    <select name="alat_id" id="alat_select" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Alat</option>
                        @foreach(\App\Models\Alat::where('stok_tersedia', '>', 0)->get() as $alat)
                            <option value="{{ $alat->alat_id }}" data-max="{{ $alat->stok_tersedia }}">
                                {{ $alat->nama_alat }} (Tersedia: {{ $alat->stok_tersedia }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <input type="number" id="jumlah_input" name="jumlah" min="1" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Jumlah yang dipinjam">
                    <p id="stok_info" class="text-xs text-gray-500 mt-1"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_peminjaman" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jatuh Tempo</label>
                    <input type="date" name="tanggal_kembali_rencana" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan Peminjaman</label>
                    <textarea name="tujuan_peminjaman" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Untuk keperluan..."></textarea>
                </div>

                <div class="flex space-x-2">
                    <button type="submit" 
                        class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition">
                        Ajukan
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
            document.getElementById('peminjamanModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('peminjamanModal').classList.add('hidden');
        }

        // Update max jumlah berdasarkan alat yang dipilih
        document.getElementById('alat_select').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const maxStok = selected.getAttribute('data-max');
            const jumlahInput = document.getElementById('jumlah_input');
            const stokInfo = document.getElementById('stok_info');
            
            if (maxStok) {
                jumlahInput.max = maxStok;
                stokInfo.textContent = `Maksimal: ${maxStok} unit`;
            } else {
                jumlahInput.max = '';
                stokInfo.textContent = '';
            }
        });

        function approveModal(id) {
            if (confirm('Setujui peminjaman ini?')) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/peminjaman/' + id;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'PUT';
                
                const status = document.createElement('input');
                status.type = 'hidden';
                status.name = 'status';
                status.value = 'disetujui';
                
                form.appendChild(csrf);
                form.appendChild(method);
                form.appendChild(status);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function rejectModal(id) {
            if (confirm('Tolak peminjaman ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/peminjaman/' + id;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'PUT';
                
                const status = document.createElement('input');
                status.type = 'hidden';
                status.name = 'status';
                status.value = 'ditolak';
                
                form.appendChild(csrf);
                form.appendChild(method);
                form.appendChild(status);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('peminjamanModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection