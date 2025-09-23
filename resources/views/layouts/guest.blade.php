<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'EkskulApp') }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-800">
    <main class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-2xl">
            @yield('content')
        </div>
    </main>
    <footer class="bg-white shadow-inner py-4 mt-10">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} EkskulApp. All rights reserved.
        </div>
    </footer>
</body>
</html>
