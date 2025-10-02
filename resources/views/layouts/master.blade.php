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

    <!-- Navbar Dashboard -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            {{-- Logo --}}
            <a class="navbar-brand d-flex align-items-center fw-bold">
                <img src="{{ asset('images/logo_fatahillah.jpg') }}" alt="Logo" width="35" height="35" style="object-fit: cover;" class="me-2 rounded-circle">
                {{ config('app.name', 'EkskulApp') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-3">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item"><a class="nav-link" href="{{route('dashboard')}}">Daftar Ekskul</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('pengumuman.index')}}">Pengumuman</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('kelola-user')}}">Users</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('ekskul.index')}}">Kelola Ekskul</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('kelola-pengumuman.manage')}}">Kelola Pengumuman</a></li>
                            
                        @elseif(Auth::user()->role === 'pembina')
                            <li class="nav-item"><a class="nav-link" href="{{route('dashboard')}}">Daftar Ekskul</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Pengumuman</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Kelola Pengumuman</a></li>
                        @elseif(Auth::user()->role === 'siswa')
                            <li class="nav-item"><a class="nav-link" href="{{route('dashboard')}}">Daftar Ekskul</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('pengumuman.index')}}">Pengumuman</a></li>
                        @endif
                    @endauth

                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{route('pengumuman.index')}}">Pengumuman</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('dashboard')}}">Daftar Ekskul</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('login')}}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('register')}}">Register</a></li>
                    @endguest
                </ul>
            </div>

            @auth
            {{-- Profile + Logout --}}
            <div class="d-flex align-items-center">
                <span class="text-white me-2">
                    <a href="{{ route('profile.show') }}" class="text-decoration-none text-white">
                        @if(Auth::user()->role === 'admin')
                        Hi, {{ Auth::user()->name }} (Admin)
                        @elseif(Auth::user()->role === 'pembina')
                        Hi, {{ Auth::user()->name }} (Pembina)
                        @elseif(Auth::user()->role === 'siswa')
                        Hi, {{ Auth::user()->name }}
                        @endif
                    </a>
                </span>
               <img src="{{ asset('images/profile/' . (Auth::user()->profile->foto ?? 'default.jpg')) }}"
                    class="rounded-circle me-2" width="45" height="45" style="object-fit: cover;" alt="User Avatar">
   
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </nav>

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