<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola DPR - Pemungutan Suara Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Header -->
    <div class="bg-red-700 text-white py-4 px-8 flex justify-between items-center shadow-lg">
        <div class="flex items-center gap-3">
            <span class="text-3xl">🗳️</span>
            <div>
                <h1 class="text-lg font-bold leading-tight">Pemungutan Suara Online</h1>
                <p class="text-red-200 text-xs">Panel Admin</p>
            </div>
        </div>
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="bg-white text-red-600 font-semibold px-4 py-2 rounded-full text-xs hover:bg-red-50 transition">📊 Dashboard</a>
            <a href="{{ route('admin.candidates') }}" class="bg-white text-red-600 font-semibold px-4 py-2 rounded-full text-xs hover:bg-red-50 transition">👤 Kelola Calon</a>
            <a href="{{ route('admin.register') }}" class="bg-white text-red-600 font-semibold px-4 py-2 rounded-full text-xs hover:bg-red-50 transition">➕ Register User</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-800 hover:bg-red-900 text-white text-xs px-4 py-2 rounded-full transition">Logout</button>
            </form>
        </div>
    </div>

    <div class="max-w-5xl mx-auto py-10 px-4">

        @if (session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">✅ {{ session('success') }}</div>
        @endif

        <!-- Form Tambah Partai -->
        <div class="bg-white rounded-2xl shadow p-8 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">➕ Tambah Partai DPR</h2>
            <form method="POST" action="{{ route('admin.dpr.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Urut</label>
                        <input type="number" name="nomor_urut" value="{{ old('nomor_urut') }}"
                            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400"
                            placeholder="Contoh: 1" required>
                        @error('nomor_urut') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Partai</label>
                        <input type="text" name="nama_partai" value="{{ old('nama_partai') }}"
                            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400"
                            placeholder="Contoh: Partai Keadilan Sejahtera" required>
                        @error('nama_partai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo Partai</label>
                        <input type="file" name="logo_partai" accept="image/*"
                            class="w-full border rounded-xl px-4 py-2 bg-white">
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, JPEG, PNG. Maks 2MB.</p>
                        @error('logo_partai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Daftar Calon Anggota -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Calon Anggota (Maks 10)</label>
                        <div id="members-list" class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="bg-red-100 text-red-600 font-bold text-xs w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0">1</span>
                                <input type="text" name="members[]"
                                    class="flex-1 border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400"
                                    placeholder="Nama calon anggota ke-1" required>
                            </div>
                        </div>
                        @error('members') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <button type="button" onclick="addMember()"
                            class="mt-3 text-red-600 text-sm font-semibold hover:underline">
                            + Tambah Calon Anggota
                        </button>
                    </div>

                </div>

                <div class="mt-6">
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-8 rounded-full transition">
                        Tambah Partai
                    </button>
                </div>
            </form>
        </div>

        <!-- Daftar Partai -->
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <h2 class="text-xl font-bold text-gray-800 px-6 py-4 border-b">📋 Daftar Partai DPR</h2>
            @forelse ($dprCandidates as $partai)
            <div class="p-5 border-b hover:bg-gray-50">
                <div class="flex items-start gap-4">
                    <!-- Logo -->
                    <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                        @if ($partai->logo_partai)
                            <img src="{{ $partai->logo_partai }}" class="w-full h-full object-contain p-1">
                        @else
                            <span class="text-2xl">🏛️</span>
                        @endif
                    </div>
                    <!-- Info -->
                    <div class="flex-1">
                        <p class="font-bold text-gray-800">No. {{ $partai->nomor_urut }} - {{ $partai->nama_partai }}</p>
                        <div class="mt-2 grid grid-cols-2 gap-1">
                            @foreach ($partai->members as $member)
                            <p class="text-sm text-gray-500">{{ $member->nomor_urut }}. {{ $member->nama }}</p>
                            @endforeach
                        </div>
                    </div>
                    <!-- Hapus -->
                    <form method="POST" action="{{ route('admin.dpr.destroy', $partai->id) }}"
                        onsubmit="return confirm('Yakin hapus partai ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">🗑️ Hapus</button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-400 py-8">Belum ada partai. Tambahkan di atas!</p>
            @endforelse
        </div>
    </div>

    <script>
        let memberCount = 1;
        function addMember() {
            if (memberCount >= 10) {
                alert('Maksimal 10 calon anggota!');
                return;
            }
            memberCount++;
            const list = document.getElementById('members-list');
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2';
            div.innerHTML = `
                <span class="bg-red-100 text-red-600 font-bold text-xs w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0">${memberCount}</span>
                <input type="text" name="members[]"
                    class="flex-1 border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400"
                    placeholder="Nama calon anggota ke-${memberCount}">
                <button type="button" onclick="this.parentElement.remove(); memberCount--;"
                    class="text-red-400 hover:text-red-600 text-lg font-bold">×</button>
            `;
            list.appendChild(div);
        }
    </script>
</body>
</html>
