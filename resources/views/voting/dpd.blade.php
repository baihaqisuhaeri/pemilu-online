<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilu Online - Pilih DPD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .card-candidate input[type="radio"]:checked + .card-inner {
            border-color: #dc2626;
            background: linear-gradient(to bottom, #fff5f5, #ffffff);
            box-shadow: 0 8px 30px rgba(220,38,38,0.2);
        }
        .card-inner { transition: all 0.3s ease; }
        .card-inner:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }
    </style>
</head>
<body class="min-h-screen" style="background: linear-gradient(135deg, #f8fafc 0%, #fee2e2 100%);">

    <!-- Header -->
    <div class="bg-red-700 text-white py-4 px-8 flex justify-between items-center shadow-lg">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="icon" class="w-16 h-16 object-contain">
            <div>
                <h1 class="text-lg font-bold leading-tight">Pemilu Online</h1>
                <p class="text-red-200 text-xs">Pemilihan DPD RI</p>
            </div>
        </div>
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('voting') }}" class="bg-white text-red-600 font-semibold px-4 py-2 rounded-full text-xs hover:bg-red-50 transition">
                ← Kembali
            </a>
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
        <h2 class="text-3xl font-extrabold text-gray-800 mb-2">Pilih Calon DPD RI</h2>
        <p class="text-gray-500">Klik pada kartu calon, lalu tekan tombol coblos di bawah</p>
        <div class="mt-3 inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-4 py-1 rounded-full">
            ⚠️ Suara hanya dapat diberikan satu kali dan tidak dapat diubah
        </div>
    </div>

    @if ($errors->any())
    <div class="max-w-xl mx-auto mb-4 bg-red-100 text-red-700 px-4 py-3 rounded-xl text-center text-sm">
        ⚠️ Harap pilih salah satu calon sebelum submit.
    </div>
    @endif

    <!-- Header Image -->
<div class="max-w-7xl mx-auto px-4 border-2 border-gray-400 rounded-xl overflow-hidden bg-white">
    <img src="{{ asset('images/header_dpd.jpg') }}" alt="Header DPR" class="w-full object-contain mt-5 mb-5">
    

    <form method="POST" action="{{ route('voting.dpd.store') }}">
        @csrf
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                @foreach ($candidates as $candidate)
                <label class="card-candidate cursor-pointer block">
                    <input type="radio" name="candidate_id" value="{{ $candidate->id }}" class="hidden" required>
                    <div class="card-inner bg-white rounded-2xl border-4 border-gray-200 overflow-hidden">

                        <!-- Nomor Urut -->
                        <div class="bg-white flex justify-center items-center py-4 border-b border-gray-100">
                            <span class="text-gray-800 font-black text-7xl">{{ $candidate->nomor_urut }}</span>
                        </div>

                        <!-- Foto -->
                        <div class="w-full h-96 bg-gray-100 overflow-hidden relative">
                            <div class="absolute inset-0 flex flex-col">
                                <div class="flex-1 bg-red-600"></div>
                                <div class="flex-1 bg-white"></div>
                            </div>
                            @if ($candidate->photo)
                                <img src="{{ $candidate->photo }}"
                                     alt="{{ $candidate->name }}"
                                     class="absolute inset-0 w-full h-full object-cover object-top z-10">
                            @else
                                <div class="absolute inset-0 z-10 flex items-center justify-center">
                                    <span class="text-5xl text-gray-400">👤</span>
                                </div>
                            @endif
                        </div>

                        <!-- Nama -->
                        <div class="p-5 text-center">
                
                            <p class="font-extrabold text-gray-800 text-2xl leading-tight mb-20">{{ $candidate->name }}</p>
                        </div>

                        <div class="selected-indicator hidden bg-red-600 text-white text-center text-sm font-bold py-2">
                            ✅ Dipilih
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
            </div>

            <div class="text-center pb-12">
                <button type="submit"
                    onclick="return confirm('Yakin dengan pilihan Anda? Suara tidak dapat diubah setelah dikonfirmasi.')"
                    class="bg-red-600 hover:bg-red-700 active:scale-95 text-white font-extrabold py-4 px-8 rounded-full text-xl shadow-lg transition-all flex items-center gap-3 mx-auto">
                    <img src="{{ asset('images/logo_coblos.png') }}" alt="icon" class="w-16 h-16 object-contain">
                    COBLOS SEKARANG
                </button>
            </div>
        </div>
    </form>

    <script>
        document.querySelectorAll('.card-candidate input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function () {
                document.querySelectorAll('.selected-indicator').forEach(el => el.classList.add('hidden'));
                this.closest('.card-candidate').querySelector('.selected-indicator').classList.remove('hidden');
            });
        });
    </script>
</body>
</html>