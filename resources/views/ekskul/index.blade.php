@extends('layouts.master')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Card Example</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

  <!-- Success Message -->
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Create New Button -->
  <div class="mb-4">
    <a href="{{ route('ekskul.create') }}" class="btn btn-primary">CREATE NEW</a>
  </div>

  <!-- Card Row -->
  <div class="row g-4">
    @forelse($ekskuls as $ekskul)
      <div class="col-md-4">
        <div class="card">
          <img src="{{ $ekskul->foto_url }}" class="card-img-top" alt="{{ $ekskul->nama_ekskul }}" style="height: 200px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title">{{ $ekskul->nama_ekskul }}</h5>
            <p class="card-text">
              {{ Str::limit($ekskul->deskripsi ?? 'Tidak ada deskripsi', 100, '...') }}
            </p>
          </div>
          <div class="card-footer p-0">
            <a href="{{ route('ekskul.show', $ekskul) }}" class="btn btn-primary w-100 rounded-0">READ MORE</a>
            @auth
              @php
                $isSiswa = auth()->user()->role === 'siswa';
                $isMember = $ekskul->anggota->contains(auth()->id());
                $isEditor = in_array(auth()->user()->role, ['admin','pembina']);
              @endphp
              @if($isSiswa)
                <div class="d-flex">
                  @if(!$isMember)
                    <form action="{{ route('ekskul.join', $ekskul) }}" method="POST" class="flex-fill">
                      @csrf
                      <button type="submit" class="btn btn-success w-100 rounded-0">
                        <i class="bi bi-plus-circle"></i> Join
                      </button>
                    </form>
                  @else
                    <form action="{{ route('ekskul.leave', $ekskul) }}" method="POST" class="flex-fill">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger w-100 rounded-0">
                        <i class="bi bi-x-circle"></i> Leave
                      </button>
                    </form>
                  @endif
                </div>
              @elseif($isEditor)
                <div class="d-flex">
                  <a href="{{ route('ekskul.edit', $ekskul) }}" class="btn btn-warning flex-fill rounded-0">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="{{ route('ekskul.destroy', $ekskul) }}" method="POST" class="flex-fill" onsubmit="return confirm('Yakin ingin menghapus ekskul ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100 rounded-0">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              @endif
            @endauth
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info text-center">
          <h5>Belum ada data ekskul</h5>
          <p>Klik tombol "CREATE NEW" untuk menambahkan ekskul pertama.</p>
        </div>
      </div>
    @endforelse
  </div>

  <!-- Pagination -->
  @if($ekskuls->hasPages())
    <div class="d-flex justify-content-center mt-4">
      {{ $ekskuls->links() }}
    </div>
  @endif

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</body>
</html>
@endsection