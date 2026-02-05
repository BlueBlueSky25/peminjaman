@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen User</h2>
        <button onclick="openModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition">
            <i class="fas fa-plus"></i>
            <span>Tambah User</span>
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

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full capitalize
                                @if($user->level == 'admin') bg-red-100 text-red-800
                                @elseif($user->level == 'petugas') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ $user->level }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                            <button onclick="editUser({{ $user->user_id }}, '{{ $user->username }}', '{{ $user->level }}')" class="text-blue-600 hover:text-blue-900" title="Edit">
                                Edit
                            </button>
                            <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-users text-4xl text-gray-300 mb-2"></i>
                            <p>Belum ada data user.</p>
                            <p class="text-sm">Klik tombol "Tambah User" untuk menambahkan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah/Edit User -->
    <div id="userModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modalTitle" class="text-lg font-bold text-gray-900">Tambah User</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="userForm" method="POST" action="{{ route('users.store') }}">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">
                <input type="hidden" id="userId" name="user_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" id="username" name="username" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Username">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Level</label>
                    <select id="level" name="level" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Level</option>
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                        <option value="peminjam">Peminjam</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password <span id="passwordOptional" class="text-xs text-gray-500"></span></label>
                    <input type="password" id="password" name="password" minlength="6"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Minimal 6 karakter">
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
            document.getElementById('userModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Tambah User';
            document.getElementById('userForm').action = '{{ route("users.store") }}';
            document.getElementById('methodField').value = 'POST';
            document.getElementById('userId').value = '';
            document.getElementById('username').value = '';
            document.getElementById('level').value = '';
            document.getElementById('password').value = '';
            document.getElementById('password').required = true;
            document.getElementById('passwordOptional').textContent = '';
        }

        function closeModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        function editUser(id, username, level) {
            document.getElementById('userModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Edit User';
            document.getElementById('userForm').action = '/users/' + id;
            document.getElementById('methodField').value = 'PUT';
            document.getElementById('userId').value = id;
            document.getElementById('username').value = username;
            document.getElementById('level').value = level;
            document.getElementById('password').value = '';
            document.getElementById('password').required = false;
            document.getElementById('passwordOptional').textContent = '(kosongkan jika tidak ingin mengubah)';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('userModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection