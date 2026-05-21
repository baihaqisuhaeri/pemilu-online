<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pemungutan Suara Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-pattern {
            background-color: #b91c1c;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="min-h-screen flex">

    <!-- Kiri - Branding -->
    <div class="hidden lg:flex w-1/2 bg-pattern flex-col items-center justify-center p-12 text-white">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-32 h-32 object-contain mb-8">
        <h1 class="text-4xl font-black mb-3 text-center">Pemungutan Suara Online</h1>
        <p class="text-red-200 text-center text-lg leading-relaxed">
            Sistem Pemilihan Umum Digital yang Aman, Transparan, dan Terpercaya
        </p>
        <div class="mt-12 grid grid-cols-3 gap-6 text-center">
            <div class="bg-white bg-opacity-10 rounded-2xl p-4">
                <div class="text-3xl mb-2">🔒</div>
                <p class="text-xs text-red-100">Aman</p>
            </div>
            <div class="bg-white bg-opacity-10 rounded-2xl p-4">
                <div class="text-3xl mb-2">📊</div>
                <p class="text-xs text-red-100">Transparan</p>
            </div>
            <div class="bg-white bg-opacity-10 rounded-2xl p-4">
                <div class="text-3xl mb-2">⚡</div>
                <p class="text-xs text-red-100">Cepat</p>
            </div>
        </div>
    </div>

    <!-- Kanan - Form Login -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
        <div class="w-full max-w-md">

            <!-- Logo mobile -->
            <div class="flex lg:hidden justify-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 h-20 object-contain">
            </div>

            <div class="text-center mb-8">
                <h2 class="text-3xl font-black text-gray-800">Selamat Datang</h2>
                <p class="text-gray-400 mt-1">Masukkan NIK dan password untuk masuk</p>
            </div>

            <!-- Alert Error -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <div class="bg-white rounded-2xl shadow-lg p-8">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- NIK -->
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NIK</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">🪪</span>
                            <input type="text" name="nik" value="{{ old('nik') }}"
                                maxlength="16"
                                class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:outline-none focus:border-red-500 transition text-sm"
                                placeholder="Masukkan 16 digit NIK" required autofocus>
                        </div>
                        @error('nik')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">🔑</span>
                            <input type="password" name="password" id="password"
                                class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-12 py-3 focus:outline-none focus:border-red-500 transition text-sm"
                                placeholder="Masukkan password" required>
                            <!-- Toggle show/hide password -->
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-sm">
                                👁️
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 active:scale-95 text-white font-bold py-3 rounded-xl transition-all text-sm shadow-md">
                        🗳️ Masuk ke Sistem
                    </button>
                </form>
            </div>

            <p class="text-center text-xs text-gray-400 mt-6">
                &copy; {{ date('Y') }} Pemungutan Suara Online. All rights reserved.
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>

</body>
</html>
