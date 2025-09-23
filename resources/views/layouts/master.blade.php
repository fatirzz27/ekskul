<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'EkskulApp') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    @auth
    <!-- Navbar Dashboard -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            {{-- Logo --}}
            <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ url('/dashboard') }}">
                <img src="{{ asset('images/logo_fatahillah.jpg') }}" alt="Logo" width="35" class="me-2 rounded-circle">
                {{ config('app.name', 'EkskulApp') }}
            </a>

            {{-- Toggler (mobile menu) --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Menu --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-3">
                    <li class="nav-item"><a class="nav-link" href="{{route('dashboard')}}">Daftar Ekskul</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Pengumuman</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Kelola Ekskul</a></li>
                </ul>
            </div>

            {{-- Profile + Logout --}}
            <div class="d-flex align-items-center">
                <span class="text-white me-2">
                    <a href="{{ route('profile.show') }}" class="text-decoration-none text-white">
                        Hi, {{ Auth::user()->name }}
                    </a>
                </span>
                <img src="https://i.pravatar.cc/40" alt="User" class="rounded-circle me-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Content -->
    <main class="flex-fill container my-5">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white py-3 border-top mt-auto">
        <div class="container text-center text-muted small">
            &copy; {{ date('Y') }} EkskulApp. All rights reserved.
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>