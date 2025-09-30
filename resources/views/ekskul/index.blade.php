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

  <!-- Create New Button - Hanya untuk admin/pembina yang login -->
  @auth
    @if(in_array(auth()->user()->role, ['admin','pembina']))
      <div class="mb-4">
        <a href="{{ route('ekskul.create') }}" class="btn btn-primary">CREATE NEW</a>
      </div>
    @endif
  @endauth

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
              @elseif($isPembina || $isAdmin)
                <div class="d-flex">
                  <a href="{{ route('ekskul.edit', $ekskul) }}" class="btn btn-warning flex-fill rounded-0">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <button type="button" class="btn btn-danger flex-fill rounded-0" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $ekskul->id }}">
                    <i class="bi bi-trash"></i>
                  </button>
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

  <!-- Delete Confirmation Modals -->
  @foreach($ekskuls as $ekskul)
    <div class="modal fade" id="deleteModal{{ $ekskul->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $ekskul->id }}" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
          <div class="modal-body text-center p-5">
            <div class="mb-4">
              <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 80px; height: 80px;">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2.5rem;"></i>
              </div>
            </div>
            <h4 class="modal-title fw-bold mb-3" id="deleteModalLabel{{ $ekskul->id }}">Are you sure to delete this?</h4>
            <p class="text-muted mb-4">This action cannot be undone. The ekskul "{{ $ekskul->nama_ekskul }}" will be permanently deleted.</p>
            <div class="d-flex gap-3 justify-content-center">
              <form action="{{ route('ekskul.destroy', $ekskul) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger px-4 py-2 fw-semibold">
                  <i class="bi bi-trash me-2"></i>Delete
                </button>
              </form>
              <button type="button" class="btn btn-secondary px-4 py-2 fw-semibold" data-bs-dismiss="modal">
                <i class="bi bi-arrow-left me-2"></i>Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endforeach

  <!-- Pagination -->
  @if($ekskuls->hasPages())
    <div class="d-flex justify-content-center mt-4">
      {{ $ekskuls->links() }}
    </div>
  @endif

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom Modal Styles -->
  <style>
    /* Custom Modal Styles */
    .modal-content {
      border-radius: 15px;
      background: white;
    }
    
    .modal-backdrop {
      background-color: rgba(0, 0, 0, 0.5);
    }
    
    .modal .btn-danger {
      background: #dc3545;
      border: none;
      border-radius: 50px;
      box-shadow: 0 2px 10px rgba(220, 53, 69, 0.3);
      transition: all 0.3s ease;
    }
    
    .modal .btn-danger:hover {
      background: #c82333;
      transform: translateY(-1px);
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    }
    
    .modal .btn-secondary {
      background: #6c757d;
      border: none;
      border-radius: 50px;
      transition: all 0.3s ease;
    }
    
    .modal .btn-secondary:hover {
      background: #5a6268;
      transform: translateY(-1px);
    }
  </style>

</body>
</html>
@endsection