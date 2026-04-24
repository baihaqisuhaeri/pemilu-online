<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Calon - Pemilu Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Header -->
    <div class="bg-red-700 text-white py-4 px-8 flex justify-between items-center shadow-lg">
        <div class="flex items-center gap-3">
            <span class="text-3xl">🗳️</span>
            <div>
                <h1 class="text-lg font-bold leading-tight">Pemilu Online</h1>
                <p class="text-red-200 text-xs">Panel Admin</p>
            </div>
        </div>
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="bg-white text-red-600 font-semibold px-4 py-2 rounded-full text-xs hover:bg-red-50 transition">📊 Dashboard</a>
            <a href="{{ route('admin.dpr') }}" class="bg-white text-red-600 font-semibold px-4 py-2 rounded-full text-xs hover:bg-red-50 transition">🏢 Kelola DPR</a>
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

        <!-- Form Tambah Calon -->
        <div class="bg-white rounded-2xl shadow p-8 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">➕ Tambah Calon</h2>
            <form method="POST" action="{{ route('admin.candidates.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- Jenis Pemilihan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pemilihan</label>
                        <select name="election_type_id" id="election_type_id" onchange="toggleWakil()"
                            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400 bg-white">
                            @foreach ($electionTypes as $type)
                            <option value="{{ $type->id }}" data-slug="{{ $type->slug }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Urut</label>
                        <input type="number" name="nomor_urut" value="{{ old('nomor_urut') }}"
                            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400"
                            placeholder="Contoh: 1" required>
                        @error('nomor_urut') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" id="label-name">Nama Calon</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400"
                            placeholder="Nama calon" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Field Wakil (hanya untuk Presiden) -->
                    <div id="wakil-field">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Calon Wakil</label>
                        <input type="text" name="wakil_name" value="{{ old('wakil_name') }}"
                            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400"
                            placeholder="Nama calon wakil">
                        @error('wakil_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                        <input type="file" name="photo" accept="image/*"
                            class="w-full border rounded-xl px-4 py-2 bg-white">
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, JPEG, PNG. Maks 2MB.</p>
                        @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2" id="visi-misi-field">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Visi Misi</label>
                        <textarea name="visi_misi" rows="3"
                            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400"
                            placeholder="Tuliskan visi misi...">{{ old('visi_misi') }}</textarea>
                    </div>

                </div>

                <div class="mt-6">
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-8 rounded-full transition">
                        Tambah Calon
                    </button>
                </div>
            </form>
        </div>

        <!-- Daftar Calon -->
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <h2 class="text-xl font-bold text-gray-800 px-6 py-4 border-b">📋 Daftar Calon Presiden & DPD</h2>
            @forelse ($candidates as $candidate)
            <div class="flex items-center gap-4 p-4 border-b hover:bg-gray-50">
                <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-200 flex-shrink-0">
                    @if ($candidate->photo)
                        <img src="{{ Storage::url($candidate->photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-xl font-bold text-gray-400">{{ $candidate->nomor_urut }}</div>
                    @endif
                </div>
                <div class="flex-1">
                    <span class="text-xs font-bold px-2 py-1 rounded-full {{ $candidate->electionType->slug === 'presiden' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                        {{ $candidate->electionType->name }}
                    </span>
                    <p class="font-bold text-gray-800 mt-1">No. {{ $candidate->nomor_urut }} - {{ $candidate->name }}</p>
                    @if ($candidate->wakil_name)
                        <p class="text-sm text-gray-500">Wakil: {{ $candidate->wakil_name }}</p>
                    @endif
                </div>
                <form method="POST" action="{{ route('admin.candidates.destroy', $candidate->id) }}"
                    onsubmit="return confirm('Yakin hapus calon ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">🗑️ Hapus</button>
                </form>
            </div>
            @empty
            <p class="text-center text-gray-400 py-8">Belum ada calon.</p>
            @endforelse
        </div>
    </div>

    <script>
        function toggleWakil() {
            const select = document.getElementById('election_type_id');
            const selectedOption = select.options[select.selectedIndex];
            const slug = selectedOption.getAttribute('data-slug');
            const wakilField = document.getElementById('wakil-field');
            const labelName = document.getElementById('label-name');

            if (slug === 'presiden') {
                wakilField.style.display = 'block';
                labelName.textContent = 'Nama Calon Presiden';
            } else {
                wakilField.style.display = 'none';
                labelName.textContent = 'Nama Calon DPD';
            }
        }
        toggleWakil(); // Jalankan saat halaman load
    </script>
</body>
</html>