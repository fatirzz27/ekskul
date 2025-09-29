@extends('layouts.master')

@section('content')
<div class="container py-4">
  <!-- Header / Back -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0 fw-bold">{{ $ekskul->nama_ekskul }}</h3>
    <a href="{{ route('ekskul.index') }}" class="btn btn-outline-secondary btn-sm">
      &larr; Kembali
    </a>
  </div>

  <!-- Hero Image -->
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
        <ul class="list-unstyled m-0" style="column-width: 260px; column-gap: 40px;">
          @foreach($ekskul->anggota as $anggota)
            <li class="d-flex align-items-center mb-2">
              <span class="d-inline-block bg-dark rounded-circle me-2" style="width:14px; height:14px;"></span>
              <span class="text-capitalize">{{ $anggota->name }}</span>
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
          <form action="{{ route('ekskul.destroy', $ekskul) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ekskul ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
              <i class="bi bi-trash"></i> Hapus
            </button>
          </form>
        </div>
      @endif
    @endauth
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
</style>
@endsection
