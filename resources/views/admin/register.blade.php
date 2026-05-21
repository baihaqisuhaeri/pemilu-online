<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User - Pemungutan Suara Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Header -->
    <div class="bg-red-700 text-white py-4 px-8 flex justify-between items-center shadow-lg">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="icon" class="w-16 h-16 object-contain">
            <div>
                <h1 class="text-lg font-bold leading-tight">Pemungutan Suara Online</h1>
                <p class="text-red-200 text-xs">Panel Admin</p>
            </div>
        </div>
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="bg-white text-red-600 font-semibold px-4 py-2 rounded-full text-xs hover:bg-red-50 transition">
                📊 Dashboard
            </a>
            <a href="{{ route('admin.candidates') }}" class="bg-white text-red-600 font-semibold px-4 py-2 rounded-full text-xs hover:bg-red-50 transition">
                👤 Kelola Calon
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-800 hover:bg-red-900 text-white text-xs px-4 py-2 rounded-full transition">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <div class="max-w-5xl mx-auto py-10 px-4">

        @if (session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">
            ✅ {{ session('success') }}
        </div>
        @endif

        <!-- Form Register -->
        <div class="bg-white rounded-2xl shadow p-8 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">➕ Tambah User</h2>
            <form method="POST" action="{{ route('admin.register.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400"
                            placeholder="Contoh: Budi Santoso" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK (16 digit)</label>
                        <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16"
                            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400"
                            placeholder="Contoh: 3201010101010001" required>
                        @error('nik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password"
                            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400"
                            placeholder="Minimal 6 karakter" required>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400"
                            placeholder="Ulangi password" required>
                    </div>

                    <!-- Role -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role"
                            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400 bg-white">
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>👤 User (Warga)</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>🔧 Admin</option>
                        </select>
                        @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="mt-6">
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-8 rounded-full transition">
                        Tambah User
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar User -->
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <h2 class="text-xl font-bold text-gray-800 px-6 py-4 border-b">📋 Daftar User</h2>
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
    <tr>
        <th class="py-3 px-6">Nama</th>
        <th class="py-3 px-6">NIK</th>
        <th class="py-3 px-6">Role</th>
        <th class="py-3 px-6">Presiden</th>
        <th class="py-3 px-6">DPR</th>
        <th class="py-3 px-6">DPD</th>
    </tr>
</thead>
<tbody>
    @forelse ($users as $user)
    <tr class="border-t hover:bg-gray-50">
        <td class="py-3 px-6 font-semibold">{{ $user->name }}</td>
        <td class="py-3 px-6 text-gray-500 font-mono">{{ $user->nik }}</td>
        <td class="py-3 px-6">
            @if ($user->role === 'admin')
                <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-full">🔧 Admin</span>
            @else
                <span class="bg-blue-100 text-blue-600 text-xs font-bold px-2 py-1 rounded-full">👤 Warga</span>
            @endif
        </td>
        @if ($user->role === 'admin')
            <td class="py-3 px-6" colspan="3"><span class="text-gray-400 text-xs">-</span></td>
        @else
            <td class="py-3 px-6">
                @if ($user->voted_presiden)
                    <span class="bg-green-100 text-green-600 text-xs font-bold px-2 py-1 rounded-full">✅ Sudah</span>
                @else
                    <span class="bg-yellow-100 text-yellow-600 text-xs font-bold px-2 py-1 rounded-full">⏳ Belum</span>
                @endif
            </td>
            <td class="py-3 px-6">
                @if ($user->voted_dpr)
                    <span class="bg-green-100 text-green-600 text-xs font-bold px-2 py-1 rounded-full">✅ Sudah</span>
                @else
                    <span class="bg-yellow-100 text-yellow-600 text-xs font-bold px-2 py-1 rounded-full">⏳ Belum</span>
                @endif
            </td>
            <td class="py-3 px-6">
                @if ($user->voted_dpd)
                    <span class="bg-green-100 text-green-600 text-xs font-bold px-2 py-1 rounded-full">✅ Sudah</span>
                @else
                    <span class="bg-yellow-100 text-yellow-600 text-xs font-bold px-2 py-1 rounded-full">⏳ Belum</span>
                @endif
            </td>
        @endif
    </tr>
    @empty
    <tr>
        <td colspan="6" class="py-8 text-center text-gray-400">Belum ada user.</td>
    </tr>
    @endforelse
</tbody>
            </table>
        </div>
    </div>

</body>
</html>
