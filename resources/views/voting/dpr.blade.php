<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilu Online - Pilih DPR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .card-dpr { transition: all 0.3s ease; }
        .card-dpr:hover { box-shadow: 0 12px 30px rgba(0,0,0,0.12); }
        .card-dpr.dipilih { border-color: #dc2626 !important; box-shadow: 0 8px 30px rgba(220,38,38,0.2); }
    </style>
</head>
<body class="min-h-screen" style="background: linear-gradient(135deg, #f8fafc 0%, #fee2e2 100%);">

    <!-- Header -->
    <div class="bg-red-700 text-white py-4 px-8 flex justify-between items-center shadow-lg">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="icon" class="w-16 h-16 object-contain">
            <div>
                <h1 class="text-lg font-bold leading-tight">Pemilu Online</h1>
                <p class="text-red-200 text-xs">Pemilihan DPR RI</p>
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
        <h2 class="text-3xl font-extrabold text-gray-800 mb-2">Pilih Partai DPR RI</h2>
        <p class="text-gray-500">Klik <strong>nama partai</strong> untuk coblos partai, atau klik <strong>nama calon</strong> untuk coblos calon</p>
        <div class="mt-3 inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-4 py-1 rounded-full">
            ⚠️ Suara hanya dapat diberikan satu kali dan tidak dapat diubah
        </div>
    </div>

    @if ($errors->any())
    <div class="max-w-xl mx-auto mb-4 bg-red-100 text-red-700 px-4 py-3 rounded-xl text-center text-sm">
        ⚠️ Harap pilih partai atau calon sebelum submit.
    </div>
    @endif

    <!-- Header Image -->
    <div class="max-w-screen-2xl mx-auto px-4 border-2 border-gray-400 rounded-xl overflow-hidden bg-white pb-6">
        <img src="{{ asset('images/header_dpd.jpg') }}" alt="Header DPR" class="w-full object-contain mt-5 mb-5">

        <form method="POST" action="{{ route('voting.dpr.store') }}" id="form-dpr">
            @csrf
            <input type="hidden" name="vote_type" id="vote_type" value="">
            <input type="hidden" name="dpr_candidate_id" id="dpr_candidate_id" value="">
            <input type="hidden" name="dpr_member_id" id="dpr_member_id" value="">

            <div class="max-w-screen-2xl mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10 items-stretch">
                    @foreach ($candidates as $candidate)
                    <div class="card-dpr bg-white rounded-2xl border-2 border-black overflow-hidden flex flex-col h-full"
                        id="card-{{ $candidate->id }}">

                        <!-- Header Partai — klik = coblos partai -->
                        <div class="flex items-center hover:bg-red-50 cursor-pointer transition"
                            data-id="{{ $candidate->id }}"
                            data-nama="{{ $candidate->nama_partai }}"
                            onclick="pilihPartai(this.dataset.id, this.dataset.nama)">
                            <div class="flex items-center justify-center px-3 py-4 min-w-[70px]">
                                <p class="text-4xl font-black text-gray-900">{{ $candidate->nomor_urut }}</p>
                            </div>
                            <div class="flex items-center justify-start px-3 py-4 w-24 h-24">
                                @if ($candidate->logo_partai)
                                    <img src="{{ ($candidate->logo_partai }}"
                                        alt="{{ $candidate->nama_partai }}"
                                        class="max-h-full max-w-full object-contain">
                                @else
                                    <span class="text-4xl">🏛️</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-start flex-1 px-3 py-4">
                                <p class="font-extrabold text-gray-900 text-lg uppercase leading-tight">{{ $candidate->nama_partai }}</p>
                            </div>
                        </div>

                        <!-- Daftar Calon — klik nama = coblos calon -->
                        @php $maxMembers = 10; @endphp
                        <div class="flex-1">
                            <ol style="border-top: 3px double #6b7280;">
                                @foreach ($candidate->members as $member)
                                <li class="flex items-center gap-2 text-sm text-gray-700 px-4 py-1 hover:bg-red-50 hover:text-red-600 cursor-pointer transition"
                                    id="member-{{ $member->id }}"
                                    data-candidate-id="{{ $candidate->id }}"
                                    data-member-id="{{ $member->id }}"
                                    data-nama-partai="{{ $candidate->nama_partai }}"
                                    data-nama-calon="{{ $member->nama }}"
                                    onclick="pilihCalon(this.dataset.candidateId, this.dataset.memberId, this.dataset.namaPartai, this.dataset.namaCalon)"
                                    @if(!$loop->last || $candidate->members->count() < 10)
                                        style="border-bottom: 3px double #6b7280;"
                                    @endif>
                                    <span class="font-bold text-xs w-6 h-6 flex items-center justify-center flex-shrink-0">
                                        {{ $member->nomor_urut }}.
                                    </span>
                                    {{ $member->nama }}
                                </li>
                                @endforeach

                                @for ($i = $candidate->members->count(); $i < $maxMembers; $i++)
                                <li class="flex items-center px-4" style="min-height: 2rem;"></li>
                                @endfor
                            </ol>
                        </div>

                        <!-- Indikator Terpilih -->
                        <div class="selected-indicator hidden bg-red-600 text-white text-center text-sm font-bold py-2">
                            ✅ Dipilih
                        </div>
                    </div>
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

            

        </form>
    </div>

    <div class="pb-32"></div>

    <script>
    function resetPilihan() {
        document.querySelectorAll('.card-dpr').forEach(card => {
            card.classList.remove('dipilih');
            card.classList.add('border-black');
        });
        document.querySelectorAll('[id^="member-"]').forEach(li => {
            li.classList.remove('bg-red-100', 'font-bold', 'text-red-600');
        });
        document.querySelectorAll('.selected-indicator').forEach(el => el.classList.add('hidden'));
    }

    function pilihPartai(candidateId, namaPartai) {
        resetPilihan();

        document.getElementById('vote_type').value = 'partai';
        document.getElementById('dpr_candidate_id').value = candidateId;
        document.getElementById('dpr_member_id').value = '';

        const card = document.getElementById('card-' + candidateId);
        card.classList.add('dipilih');
        card.classList.remove('border-black');
        card.querySelector('.selected-indicator').textContent = '✅ Partai Dipilih';
        card.querySelector('.selected-indicator').classList.remove('hidden');
    }

    function pilihCalon(candidateId, memberId, namaPartai, namaCalon) {
        resetPilihan();

        document.getElementById('vote_type').value = 'calon';
        document.getElementById('dpr_candidate_id').value = candidateId;
        document.getElementById('dpr_member_id').value = memberId;

        const card = document.getElementById('card-' + candidateId);
        card.classList.add('dipilih');
        card.classList.remove('border-black');
        card.querySelector('.selected-indicator').textContent = '✅ Calon: ' + namaCalon;
        card.querySelector('.selected-indicator').classList.remove('hidden');

        const member = document.getElementById('member-' + memberId);
        member.classList.add('bg-red-100', 'font-bold', 'text-red-600');
    }
</script>

</body>
</html>