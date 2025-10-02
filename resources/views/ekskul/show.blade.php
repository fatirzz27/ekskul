@extends('layouts.master')

@section('content')
<div class="container py-4">
  <!-- Header / Back -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0 fw-bold">{{ $ekskul->nama_ekskul }}</h3>
    @auth
      <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
        &larr; Kembali
      </a>
    @else
      <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm">
        &larr; Kembali
      </a>
    @endauth
  </div>

    @auth
      @if($ekskul->pembina->contains(Auth::id()))
        <div class="d-flex gap-2 mb-4">
          <a href="{{ route('pembina.absensi.index', $ekskul->id) }}" class="btn btn-primary">
            <i class="bi bi-check2-square"></i> Absensi
          </a>
          <a href="#" class="btn btn-primary">
            <i class="bi bi-journal-text"></i> Laporan Absensi
          </a>
          <a href="{{ route('pembina.anggota.index', $ekskul->id) }}" class="btn btn-primary">
  <i class="bi bi-people"></i> Kelola Anggota
</a>


        </div>
      @endif
    @endauth
  <div class="ratio ratio-21x9 mb-4 rounded border border-primary overflow-hidden">
    <img src="{{ $ekskul->foto_url }}" alt="{{ $ekskul->nama_ekskul }}" class="w-100 h-100" style="object-fit:cover;">
  </div>

  <!-- Content Container dengan lebar sama seperti foto -->
  <div class="content-container">
    <!-- Deskripsi -->
    <div class="mb-5">
      <p class="lh-lg" style="text-align: justify;">
        {{ $ekskul->deskripsi ?? 'Belum ada deskripsi untuk ekskul ini.' }}
      </p>
    </div>

  

    <!-- Anggota Ekskul -->
    <div class="mb-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Anggota ekskul :</h6>
        @auth
          @php $user = auth()->user(); @endphp
          @if($user->role === 'siswa')
            @if(!$isMember)
              <form action="{{ route('ekskul.join', $ekskul) }}" method="POST">
                @csrf
                <button class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i> Join</button>
              </form>
            @else
              <form action="{{ route('ekskul.leave', $ekskul) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger btn-sm"><i class="bi bi-x-circle"></i> Leave</button>
              </form>
            @endif
          @endif
        @endauth
      </div>
      @if($ekskul->anggota->count())
        <ul class="list-unstyled m-0">
          @foreach($ekskul->anggota as $anggota)
            <li class="d-flex align-items-center mb-2">
              <span class="d-inline-block bg-dark rounded-circle me-2" style="width:14px; height:14px;"></span>
              <span class="text-capitalize">{{ $anggota->name }}</span>
              <span class="badge bg-light text-dark ms-2 small">
                @if($anggota->profile && $anggota->profile->jenis_kelamin == 'L')
                  Laki-laki
                @elseif($anggota->profile && $anggota->profile->jenis_kelamin == 'P')
                  Perempuan
                @else
                  N/A
                @endif
              </span>
            </li>
          @endforeach
        </ul>
      @else
        <p class="text-muted fst-italic mb-0">Belum ada anggota.</p>
      @endif
    </div>

    <!-- Meta Info & Admin Actions -->
    <div class="d-flex flex-wrap gap-3 align-items-center mb-3 small text-muted">
      <span><i class="bi bi-calendar-event"></i> Dibuat: {{ $ekskul->created_at->format('d M Y H:i') }}</span>
      <span><i class="bi bi-clock-history"></i> Diperbarui: {{ $ekskul->updated_at->diffForHumans() }}</span>
    </div>

    @auth
      @if(in_array(Auth::user()->role, ['admin','pembina']))
        <div class="d-flex gap-2 mb-5">
          <a href="{{ route('ekskul.edit', $ekskul) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
          </a>
          @elseif(Auth::user()->role === 'admin')
          <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
            <i class="bi bi-trash"></i> Hapus
          </button>
        </div>
      @endif
    @endauth
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-body text-center p-5">
        <div class="mb-4">
          <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 80px; height: 80px;">
            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2.5rem;"></i>
          </div>
        </div>
        <h4 class="modal-title fw-bold mb-3" id="deleteModalLabel">Are you sure to delete this?</h4>
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

<!-- Styles khusus -->
<style>
  .ratio-21x9 { position: relative; width:100%; }
  .ratio-21x9:before { content:""; display:block; padding-top: calc(100% * 9 / 21); }
  .ratio-21x9 > * { position:absolute; inset:0; }
  
  .content-container {
    width: 100%;
  }
  
  /* Custom Modal Styles */
  .modal-content {
    border-radius: 15px;
    background: white;
  }
  
  .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
  }
  
  #deleteModal .btn-danger {
    background: #dc3545;
    border: none;
    border-radius: 50px;
    box-shadow: 0 2px 10px rgba(220, 53, 69, 0.3);
    transition: all 0.3s ease;
  }
  
  #deleteModal .btn-danger:hover {
    background: #c82333;
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
  }
  
  #deleteModal .btn-secondary {
    background: #6c757d;
    border: none;
    border-radius: 50px;
    transition: all 0.3s ease;
  }
  
  #deleteModal .btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-1px);
  }
</style>
@endsection
