<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pemilu Online</title>
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
            <a href="{{ route('admin.candidates') }}" class="bg-white text-red-600 font-semibold px-4 py-2 rounded-full text-xs hover:bg-red-50 transition">👤 Kelola Calon</a>
            <a href="{{ route('admin.dpr') }}" class="bg-white text-red-600 font-semibold px-4 py-2 rounded-full text-xs hover:bg-red-50 transition">🏢 Kelola DPR</a>
            <a href="{{ route('admin.register') }}" class="bg-white text-red-600 font-semibold px-4 py-2 rounded-full text-xs hover:bg-red-50 transition">➕ Register User</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-800 hover:bg-red-900 text-white text-xs px-4 py-2 rounded-full transition">Logout</button>
            </form>
        </div>
    </div>

    <div class="max-w-6xl mx-auto py-10 px-4">

        @php
            $presiden = $candidates->filter(fn($c) => $c->electionType && $c->electionType->slug === 'presiden');
            $dpd = $candidates->filter(fn($c) => $c->electionType && $c->electionType->slug === 'dpd');
            $totalPresiden = $presiden->sum('votes_count');
            $totalDpd = $dpd->sum('votes_count');
            $totalDpr = $dprCandidates->sum('votes_count');
        @endphp

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-2xl shadow p-6 text-center">
                <p class="text-gray-400 text-sm mb-1">Total Suara Presiden</p>
                <p class="text-4xl font-black text-red-600">{{ $totalPresiden }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 text-center">
                <p class="text-gray-400 text-sm mb-1">Total Suara DPR</p>
                <p class="text-4xl font-black text-red-600">{{ $totalDpr }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 text-center">
                <p class="text-gray-400 text-sm mb-1">Total Suara DPD</p>
                <p class="text-4xl font-black text-red-600">{{ $totalDpd }}</p>
            </div>
        </div>

        <!-- ===================== -->
        <!-- Hasil Presiden -->
        <!-- ===================== -->
        <div class="bg-white rounded-2xl shadow overflow-hidden mb-8">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h2 class="font-bold text-gray-800 text-lg">🏛️ Hasil Pemilihan Presiden</h2>
                <div class="flex gap-3">
                    <a href="{{ route('admin.export', ['format' => 'xlsx', 'type' => 'presiden']) }}"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full text-xs transition">
                        📥 Export Excel
                    </a>
                    <a href="{{ route('admin.export', ['format' => 'csv', 'type' => 'presiden']) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full text-xs transition">
                        📥 Export CSV
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
                @forelse ($presiden as $candidate)
                @php $percent = $totalPresiden > 0 ? ($candidate->votes_count / $totalPresiden) * 100 : 0; @endphp
                <div class="bg-gray-50 rounded-xl overflow-hidden">
                    <div class="h-40 bg-gray-200 overflow-hidden">
                        @if ($candidate->photo)
                            <img src="{{ $candidate->photo }}" class="w-full h-full object-cover object-top">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-4xl font-black text-gray-400">{{ $candidate->nomor_urut }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <p class="font-bold text-gray-800 text-sm">No. {{ $candidate->nomor_urut }} - {{ $candidate->name }}</p>
                        @if ($candidate->wakil_name)
                            <p class="text-xs text-gray-500">& {{ $candidate->wakil_name }}</p>
                        @endif
                        <div class="bg-gray-200 rounded-full h-2 mt-2 mb-1">
                            @php $percentStyle = number_format($percent, 1) . '%'; @endphp
                            <div class="bg-red-500 h-2 rounded-full" style="width: <?php echo number_format($percent, 1); ?>%"></div>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="font-bold text-gray-700">{{ $candidate->votes_count }} suara</span>
                            <span class="text-gray-400">{{ number_format($percent, 1) }}%</span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-sm col-span-3 text-center py-4">Belum ada data calon presiden.</p>
                @endforelse
            </div>
        </div>

        <!-- ===================== -->
        <!-- Hasil DPR -->
        <!-- ===================== -->
        <div class="bg-white rounded-2xl shadow overflow-hidden mb-8">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h2 class="font-bold text-gray-800 text-lg">🏢 Hasil Pemilihan DPR</h2>
                <div class="flex gap-3">
                    <a href="{{ route('admin.export', ['format' => 'xlsx', 'type' => 'dpr']) }}"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full text-xs transition">
                        📥 Export Excel
                    </a>
                    <a href="{{ route('admin.export', ['format' => 'csv', 'type' => 'dpr']) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full text-xs transition">
                        📥 Export CSV
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
                @forelse ($dprCandidates as $partai)
                @php
                    $percent = $totalDpr > 0 ? ($partai->votes_count / $totalDpr) * 100 : 0;
                    $percentStyle = number_format($percent, 1) . '%';
                @endphp
                <div class="bg-gray-50 rounded-xl overflow-hidden mb-4">
                    <!-- Header Partai -->
                    <div class="flex items-center gap-4 p-4 border-b">
                        <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0 p-1">
                            @if ($partai->logo_partai)
                                <img src="{{ $partai->logo_partai }}" class="max-h-full max-w-full object-contain">
                            @else
                                <span class="text-2xl">🏛️</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-800">No. {{ $partai->nomor_urut }} - {{ $partai->nama_partai }}</p>
                            <div class="bg-gray-200 rounded-full h-2 mt-1 mb-1">
                                <div class="bg-red-500 h-2 rounded-full" style="width: <?php echo number_format($percent, 1); ?>%"></div>
                            </div>
                            <div class="flex gap-4 text-xs">
                                <span class="font-bold text-gray-700">Suara Partai: {{ $partai->votes_count }}</span>
                                <span class="text-gray-400">{{ number_format($percent, 1) }}%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Suara Per Calon -->
                    @if ($partai->members->count() > 0)
                    <div class="p-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-2 font-semibold">Suara Per Calon</p>
                        @foreach ($partai->members as $member)
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs text-gray-500 w-4">{{ $member->nomor_urut }}.</span>
                            <span class="text-sm text-gray-700 flex-1">{{ $member->nama }}</span>
                            <span class="text-xs font-bold text-gray-700">{{ $member->votes_count }} suara</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @empty
                <p class="text-gray-400 text-sm col-span-3 text-center py-4">Belum ada data partai DPR.</p>
                @endforelse
            </div>
        </div>

        <!-- ===================== -->
        <!-- Hasil DPD -->
        <!-- ===================== -->
        <div class="bg-white rounded-2xl shadow overflow-hidden mb-8">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h2 class="font-bold text-gray-800 text-lg">🏠 Hasil Pemilihan DPD</h2>
                <div class="flex gap-3">
                    <a href="{{ route('admin.export', ['format' => 'xlsx', 'type' => 'dpd']) }}"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full text-xs transition">
                        📥 Export Excel
                    </a>
                    <a href="{{ route('admin.export', ['format' => 'csv', 'type' => 'dpd']) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full text-xs transition">
                        📥 Export CSV
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
                @forelse ($dpd as $candidate)
                @php
                    $percent = $totalDpd > 0 ? ($candidate->votes_count / $totalDpd) * 100 : 0;
                    $percentStyle = number_format($percent, 1) . '%';
                @endphp
                <div class="bg-gray-50 rounded-xl overflow-hidden">
                    <div class="h-40 bg-gray-200 overflow-hidden">
                        @if ($candidate->photo)
                            <img src="{{ $candidate->photo }}" class="w-full h-full object-cover object-top">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-4xl font-black text-gray-400">{{ $candidate->nomor_urut }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <p class="font-bold text-gray-800 text-sm">No. {{ $candidate->nomor_urut }} - {{ $candidate->name }}</p>
                        <div class="bg-gray-200 rounded-full h-2 mt-2 mb-1">
                            <div class="bg-red-500 h-2 rounded-full" style="width: <?php echo number_format($percent, 1); ?>%"></div>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="font-bold text-gray-700">{{ $candidate->votes_count }} suara</span>
                            <span class="text-gray-400">{{ number_format($percent, 1) }}%</span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-sm col-span-3 text-center py-4">Belum ada data calon DPD.</p>
                @endforelse
            </div>
        </div>

        <!-- Reset Voting -->
<div class="bg-white rounded-2xl shadow p-6 mb-8">
    <h2 class="font-bold text-gray-800 text-lg mb-4">🔄 Reset Data Voting</h2>
    <div class="flex flex-wrap gap-3">

        <!-- Reset Presiden -->
        <form method="POST" action="{{ route('admin.reset.voting.type', 'presiden') }}"
            onsubmit="return confirm('Yakin reset semua suara Presiden? Data tidak dapat dikembalikan!')">
            @csrf
            <button type="submit"
                class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-5 rounded-full text-xs transition">
                🔄 Reset Presiden
            </button>
        </form>

        <!-- Reset DPR -->
        <form method="POST" action="{{ route('admin.reset.voting.type', 'dpr') }}"
            onsubmit="return confirm('Yakin reset semua suara DPR? Data tidak dapat dikembalikan!')">
            @csrf
            <button type="submit"
                class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-5 rounded-full text-xs transition">
                🔄 Reset DPR
            </button>
        </form>

        <!-- Reset DPD -->
        <form method="POST" action="{{ route('admin.reset.voting.type', 'dpd') }}"
            onsubmit="return confirm('Yakin reset semua suara DPD? Data tidak dapat dikembalikan!')">
            @csrf
            <button type="submit"
                class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-5 rounded-full text-xs transition">
                🔄 Reset DPD
            </button>
        </form>

        <!-- Reset Semua -->
        <form method="POST" action="{{ route('admin.reset.voting') }}"
            onsubmit="return confirm('Yakin reset SEMUA data voting? Semua suara akan terhapus dan tidak dapat dikembalikan!')">
            @csrf
            <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-5 rounded-full text-xs transition">
                ⚠️ Reset Semua
            </button>
        </form>

    </div>

    @if (session('success'))
    <div class="mt-4 bg-green-100 text-green-700 px-4 py-2 rounded-xl text-sm">
        ✅ {{ session('success') }}
    </div>
    @endif
</div>

    </div>
</body>
</html>
