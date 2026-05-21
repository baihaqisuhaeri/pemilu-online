<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-lg p-10 text-center max-w-md">
        <div class="text-6xl mb-4">✅</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Suara Anda Telah Tercatat!</h1>
        <p class="text-gray-500 mb-6">Terima kasih sudah berpartisipasi dalam Pemungutan Suara Online.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-full hover:bg-red-700">
                Logout
            </button>
        </form>
    </div>
</body>
</html>
