<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemungutan Suara Online - Pilih Pemilihan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen" style="background: linear-gradient(135deg, #f8fafc 0%, #fee2e2 100%);">

    <!-- Header -->
    <div class="bg-red-700 text-white py-4 px-8 flex justify-between items-center shadow-lg">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="icon" class="w-16 h-16 object-contain">
            <div>
                <h1 class="text-lg font-bold leading-tight">Pemungutan Suara Online</h1>
                <p class="text-red-200 text-xs">Sistem Pemilihan Umum Digital</p>
            </div>
        </div>
        <div class="flex items-center gap-4 text-sm">
            <div class="text-right">
                <p class="text-red-200 text-xs">Logged in sebagai</p>
                <p class="font-semibold">{{ auth()->user()->name }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-800 hover:bg-red-900 text-white text-xs px-4 py-2 rounded-full transition">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Hero -->
    <div class="text-center py-10 px-4">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-2">Selamat Datang, {{ auth()->user()->name }}!</h2>
        <p class="text-gray-500">Pilih jenis pemilihan yang ingin Anda ikuti</p>
        <div class="mt-3 inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-4 py-1 rounded-full">
            ⚠️ Setiap pemilihan hanya dapat dilakukan satu kali
        </div>
    </div>

    @if (session('success'))
    <div class="max-w-xl mx-auto mb-4 bg-green-100 text-green-700 px-4 py-3 rounded-xl text-center text-sm">
        ✅ {{ session('success') }}
    </div>
    @endif

    @if (session('info'))
    <div class="max-w-xl mx-auto mb-4 bg-blue-100 text-blue-700 px-4 py-3 rounded-xl text-center text-sm">
        ℹ️ {{ session('info') }}
    </div>
    @endif

    <!-- Kartu Pilih Pemilihan -->
    <div class="max-w-4xl mx-auto px-4 pb-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Presiden -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-4 {{ auth()->user()->voted_presiden ? 'border-green-400' : 'border-gray-200' }}">
                <div class="bg-red-600 py-6 flex items-center justify-center">
                    <span class="text-6xl">🏛️</span>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-extrabold text-gray-800 mb-2">Presiden & Wakil</h3>
                    <p class="text-sm text-gray-500 mb-4">Pilih Presiden dan Wakil Presiden Republik Indonesia</p>
                    @if (auth()->user()->voted_presiden)
                        <div class="bg-green-100 text-green-600 font-bold py-2 px-4 rounded-full text-sm">
                            ✅ Sudah Dipilih
                        </div>
                    @else
                        <a href="{{ route('voting.presiden') }}"
                            class="block bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full text-sm transition">
                            Coblos Sekarang
                        </a>
                    @endif
                </div>
            </div>

            <!-- DPR -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-4 {{ auth()->user()->voted_dpr ? 'border-green-400' : 'border-gray-200' }}">
                <div class="bg-red-600 py-6 flex items-center justify-center">
                    <span class="text-6xl">🏢</span>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-extrabold text-gray-800 mb-2">DPR RI</h3>
                    <p class="text-sm text-gray-500 mb-4">Pilih Partai untuk Dewan Perwakilan Rakyat</p>
                    @if (auth()->user()->voted_dpr)
                        <div class="bg-green-100 text-green-600 font-bold py-2 px-4 rounded-full text-sm">
                            ✅ Sudah Dipilih
                        </div>
                    @else
                        <a href="{{ route('voting.dpr') }}"
                            class="block bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full text-sm transition">
                            Coblos Sekarang
                        </a>
                    @endif
                </div>
            </div>

            <!-- DPD -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-4 {{ auth()->user()->voted_dpd ? 'border-green-400' : 'border-gray-200' }}">
                <div class="bg-red-600 py-6 flex items-center justify-center">
                    <span class="text-6xl">🏠</span>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-extrabold text-gray-800 mb-2">DPD RI</h3>
                    <p class="text-sm text-gray-500 mb-4">Pilih Calon Dewan Perwakilan Daerah</p>
                    @if (auth()->user()->voted_dpd)
                        <div class="bg-green-100 text-green-600 font-bold py-2 px-4 rounded-full text-sm">
                            ✅ Sudah Dipilih
                        </div>
                    @else
                        <a href="{{ route('voting.dpd') }}"
                            class="block bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full text-sm transition">
                            Coblos Sekarang
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>

</body>
</html>
